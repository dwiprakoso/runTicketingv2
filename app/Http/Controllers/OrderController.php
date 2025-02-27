<?php

namespace App\Http\Controllers;

use App\Models\AddOn;
use App\Models\Order;
use App\Models\TicketCategory;
use App\Models\User;
use App\Models\Voucher;
use App\Models\OrderVoucher;
use App\Models\Payment;
use App\Jobs\SendPaymentReminderEmail;
use App\Jobs\ExpireOrderJob;
use App\Mail\OrderConfirmation;
use App\Mail\OrderRejected;
use App\Mail\TicketEmail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
// Tambahkan import ini:
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    public function showOrderForm($categoryId)
    {
        $category = TicketCategory::findOrFail($categoryId);
        
        if ($category->availableQuota() <= 0) {
            return redirect()->route('home')->with('error', 'Tiket untuk kategori ini telah habis.');
        }
        
        return view('orders.create', compact('category'));
    }
    
    public function store(Request $request)
    {
        // Get the category first to use its name
        $category = TicketCategory::findOrFail($request->ticket_category_id);
        
        // Base validation rules - kode validasi tetap sama
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|string|max:20',
            'gender' => 'required|string|in:laki-laki,perempuan',
            'nik' => 'required|string',
            'gol_darah' => 'required|string|in:A,B,AB,O',
            'alamat' => 'required|string',
            'size_chart' => 'required|string|in:S,M,L,XL,XXL',
            'bib_name' => 'required|string|max:255',
            'komunitas' => 'nullable|string|max:255',
            'kontak_darurat_name' => 'required|string|max:255',
            'kontak_darurat_no' => 'required|string|max:20',
            'ticket_category_id' => 'required|exists:ticket_categories,id',
        ];
        
        // Tambahkan aturan validasi berdasarkan kategori - kode validasi tetap sama
        if ($category->name === 'Umum' || $category->name === 'Family Run') {
            $rules['tgl_lahir'] = 'required|date';
        }
        
        if ($category->name === 'Kids 3K') {
            $rules['tgl_lahir_anak'] = ['required', 'date', function ($attribute, $value, $fail) {
                $birthdate = Carbon::parse($value);
                $maxAgeDate = Carbon::now()->subYears(12); // Maksimal usia 12 tahun
                $minAgeDate = Carbon::now(); // Minimal usia 0 tahun
                
                if ($birthdate->lt($maxAgeDate)) {
                    $fail('Usia anak maksimal adalah 12 tahun.');
                }
                
                if ($birthdate->gt($minAgeDate)) {
                    $fail('Tanggal lahir tidak boleh di masa depan.');
                }
            }];
        }
        
        if ($category->name === 'Umum') {
            $rules['jarak_lari'] = 'required|string|in:3K,7K';
        }
        
        if ($category->name === 'Family Run') {
            $rules['nama_anak'] = 'required|string|max:255';
            $rules['usia_anak'] = 'required|string';
            $rules['size_anak'] = 'required|string|in:XS,S,M,L,XL,XXL';
            $rules['bib_anak'] = 'required|string|max:255';
        }
        
        // Run validation
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Check if category has available quota
        if ($category->availableQuota() <= 0) {
            return redirect()->route('home')->with('error', 'Tiket untuk kategori ini telah habis.');
        }
        
        try {
            DB::beginTransaction();
            
            // PERUBAHAN: Selalu buat user baru, tanpa pengecekan email yang sama
            $user = new User;
            $user->email = $request->email;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->no_hp = $request->no_hp;
            $user->nik = $request->nik;
            $user->gender = $request->gender;
            
            // Handle tanggal lahir sesuai kategori
            if ($category->name === 'Umum' || $category->name === 'Family Run') {
                $user->tgl_lahir = $request->tgl_lahir;
            } elseif ($category->name === 'Kids 3K') {
                $user->tgl_lahir = $request->tgl_lahir_anak;
            }
            
            $user->gol_darah = $request->gol_darah;
            $user->alamat = $request->alamat;
            $user->komunitas = $request->komunitas;
            $user->kontak_darurat_name = $request->kontak_darurat_name;
            $user->kontak_darurat_no = $request->kontak_darurat_no;
            $user->save();
            
            $totalPrice = $category->price;
            $paymentDeadline = Carbon::now()->addHour();
            
            // Generate order number
            $today = Carbon::now()->format('Ymd');
            $prefix = 'RUN-' . $today . '-';
            
            // Gunakan Cache untuk atomic operations
            $orderNumber = Cache::lock('order_number_lock', 10)->get(function () use ($prefix) {
                $lastOrder = Order::where('order_number', 'like', $prefix . '%')
                    ->orderBy('id', 'desc')
                    ->first();
                    
                if ($lastOrder) {
                    $lastNumber = substr($lastOrder->order_number, strlen($prefix));
                    $newNumber = (int)$lastNumber + 1;
                    return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
                }
                
                return $prefix . '0001';
            });
            
            // Buat order baru dengan semua data dari request dan order number
            $orderData = [
                'user_id' => $user->id,
                'ticket_category_id' => $category->id,
                'order_number' => $orderNumber, // Tambahkan order number
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_deadline' => $paymentDeadline,
                'size_chart' => $request->size_chart,
                'bib_name' => $request->bib_name,
                // Data yang sebelumnya disimpan di user, sekarang disimpan per order
                'gender' => $request->gender,
                'nik' => $request->nik,
                'gol_darah' => $request->gol_darah,
                'alamat' => $request->alamat,
                'komunitas' => $request->komunitas,
                'kontak_darurat_name' => $request->kontak_darurat_name,
                'kontak_darurat_no' => $request->kontak_darurat_no
            ];
            
            // Tambahkan field spesifik kategori
            if ($category->name === 'Umum') {
                $orderData['jarak_lari'] = $request->jarak_lari;
                $orderData['tgl_lahir'] = $request->tgl_lahir;
            } elseif ($category->name === 'Family Run') {
                $orderData['nama_anak'] = $request->nama_anak;
                $orderData['usia_anak'] = $request->usia_anak;
                $orderData['size_anak'] = $request->size_anak;
                $orderData['bib_anak'] = $request->bib_anak;
                $orderData['tgl_lahir'] = $request->tgl_lahir;
            } elseif ($category->name === 'Kids 3K') {
                $orderData['tgl_lahir_anak'] = $request->tgl_lahir_anak;
            }
            
            $order = Order::create($orderData);
            
            Log::info('Order created with ID: ' . $order->id . ' and Order Number: ' . $orderNumber);
            
            if (!$order->id) {
                throw new \Exception('Order ID is null after creation');
            }
            
            $payment = Payment::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'amount' => $totalPrice,
            ]);
            
            Log::info('Payment created with ID: ' . $payment->id . ' for order: ' . $order->id);
            
            DB::commit();
            
            // Dispatch jobs for reminders and expiration
            SendPaymentReminderEmail::dispatch($order)->delay(Carbon::now()->addMinutes(30));
            ExpireOrderJob::dispatch($order)->delay($paymentDeadline);
            
            return redirect()->route('orders.payment', $order->id);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
    
    public function showPayment($orderId)
    {
        try {
            $order = Order::with(['ticketCategory', 'payment', 'user'])->findOrFail($orderId);
            
            if (!$order->payment) {
                Log::warning('Payment record not found for order #' . $order->id . ', creating one');
                Payment::create([
                    'order_id' => $order->id,
                    'status' => 'pending',
                    'amount' => $order->total_price,
                ]);
                
                $order = Order::with(['ticketCategory', 'payment', 'user'])->findOrFail($orderId);
            }
            
            $orderVoucher = OrderVoucher::where('order_id', $order->id)->first();
            $voucher = null;
            if ($orderVoucher) {
                $voucher = Voucher::find($orderVoucher->voucher_id);
            }
            
            if ($order->status !== 'pending') {
                return redirect()->route('home')->with('error', 'Order ini tidak dalam status pending.');
            }
            
            return view('orders.payment', compact('order', 'voucher'));
        } catch (\Exception $e) {
            Log::error('Error in showPayment: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Order tidak ditemukan.');
        }
    }
    
    public function applyVoucher(Request $request, $orderId)
    {
        $requestId = uniqid('voucher_');
        Log::info("START apply voucher [{$requestId}] for order #{$orderId}");
        
        return DB::transaction(function () use ($request, $orderId, $requestId) {
            try {
                // Validasi voucher code
                $request->validate([
                    'voucher_code' => 'required|string|exists:vouchers,code',
                ]);
                
                Log::info("Validated voucher [{$requestId}]");
                
                // Temukan order 
                $order = Order::findOrFail($orderId);
                
                // Cek jika voucher sudah ada untuk order ini
                $existingVoucher = OrderVoucher::where('order_id', $order->id)->first();
                if ($existingVoucher) {
                    Log::info("Voucher already applied [{$requestId}]");
                    return redirect()->back()->with('info', 'Voucher sudah diterapkan pada order ini.');
                }
                
                // Temukan voucher
                $voucher = Voucher::where('code', $request->voucher_code)->first();
                
                if (!$voucher) {
                    throw new \Exception('Voucher tidak ditemukan.');
                }
                
                // Cek ketersediaan kuota
                if ($voucher->availableQuota() <= 0) {
                    throw new \Exception('Voucher telah habis.');
                }
                
                Log::info("Creating order voucher [{$requestId}]");
                
                // Buat OrderVoucher (TIDAK PERLU MENGURANGI KUOTA SECARA MANUAL)
                // Kuota akan dihitung dari relasi OrderVoucher di availableQuota()
                OrderVoucher::create([
                    'order_id' => $order->id,
                    'voucher_id' => $voucher->id,
                ]);
                
                // Update total harga
                $newTotalPrice = max(0, $order->total_price - $voucher->discount_amount);
                $order->total_price = $newTotalPrice;
                $order->save();
                
                Log::info("Updated order price to {$newTotalPrice} [{$requestId}]");
                
                // Update pembayaran jika ada
                if ($order->payment) {
                    $order->payment->amount = $newTotalPrice;
                    $order->payment->save();
                    Log::info("Updated payment amount [{$requestId}]");
                }
                
                Log::info("END apply voucher [{$requestId}]: SUCCESS");
                return redirect()->back()->with('success', 'Voucher berhasil diterapkan.');
                
            } catch (\Exception $e) {
                Log::error("ERROR apply voucher [{$requestId}]: " . $e->getMessage());
                return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
            }
        });
    }




    
    public function uploadPaymentProof(Request $request, $orderId)
    {
        try {
            // Detailed logging and handling of payment proof upload
            $order = Order::findOrFail($orderId);
            
            if ($order->status !== 'pending') {
                return redirect()->route('home')->with('error', 'Order ini tidak dalam status yang memungkinkan untuk pembayaran.');
            }
            
            if ($request->has('is_free_voucher') && $order->total_price <= 0) {
                // Handle free voucher logic
                $payment = Payment::where('order_id', $order->id)->first();
                
                if (!$payment) {
                    $payment = new Payment();
                    $payment->order_id = $order->id;
                }
                
                $payment->amount = 0;
                $payment->status = 'completed';
                $payment->proof_image = 'GRATIS';
                $payment->save();
                
                $order->status = 'paid';
                $order->save();
                
                return redirect()->route('orders.success', $order->id)->with('success', 'Pembayaran berhasil dikonfirmasi secara otomatis karena gratis.');
            }
            
            $validatedData = $request->validate([
                'proof_image' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);
            
            $path = $request->file('proof_image')->store('payment_proofs', 'public');
            
            $payment = Payment::where('order_id', $order->id)->first();
            
            if (!$payment) {
                $payment = new Payment();
                $payment->order_id = $order->id;
            }
            
            $payment->amount = $order->total_price;
            $payment->status = 'pending';
            $payment->proof_image = $path;
            $payment->payment_method = 'transfer';
            $payment->save();
            
            return redirect()->route('orders.success', $order->id)->with('success', 'Bukti pembayaran berhasil diunggah dan sedang menunggu konfirmasi.');
        } catch (\Exception $e) {
            Log::error('Payment proof upload failed: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function showSuccess($orderId)
    {
        try {
            $order = Order::with(['ticketCategory', 'payment', 'user'])->findOrFail($orderId);
            return view('orders.success', compact('order'));
        } catch (\Exception $e) {
            Log::error('Error in showSuccess: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Order tidak ditemukan.');
        }
    }
    
    // public function waitingConfirmation($orderId)
    // {
    //     try {
    //         $order = Order::with(['ticketCategory', 'payment', 'user'])->findOrFail($orderId);
    //         return view('orders.waiting-confirmation', compact('order'));
    //     } catch (\Exception $e) {
    //         Log::error('Error in waitingConfirmation: ' . $e->getMessage());
    //         return redirect()->route('home')->with('error', 'Order tidak ditemukan.');
    //     }
    // }
}
