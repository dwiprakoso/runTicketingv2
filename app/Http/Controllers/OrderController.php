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
    // Tambahkan logging untuk debugging
    Log::info('Kategori ticket request: ' . $request->ticket_category_id);
    Log::info('Nilai tgl_lahir_anak yang diterima: ' . $request->tgl_lahir_anak);
    
    // Get the category first to use its name
    $category = TicketCategory::findOrFail($request->ticket_category_id);
    
    Log::info('Kategori tiket: ' . $category->name);
    
    // Base validation rules
    $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'no_hp' => 'required|string|max:20',
        'gender' => 'required|string|in:laki-laki,perempuan',
        'nik' => 'required|string',
        'gol_darah' => 'required|string|in:A,B,AB,O',
        'alamat' => 'required|string',
        'bib_name' => 'required|string|max:255',
        'komunitas' => 'nullable|string|max:255',
        'kontak_darurat_name' => 'required|string|max:255',
        'kontak_darurat_no' => 'required|string|max:20',
        'ticket_category_id' => 'required|exists:ticket_categories,id',
    ];

    // Validation rules for specific categories
    if ($category->name === 'Fun Run' || $category->name === 'Family Run' || $category->name === 'Early Bird - Fun Run 7K') {
        $rules['tgl_lahir'] = 'required|date';
        $rules['size_chart'] = 'required|string|in:S,M,L,XL,XXL';
    }

    if ($category->name === 'Kids 3K') {
        $rules['tgl_lahir_anak'] = ['required', 'date', function ($attribute, $value, $fail) {
            $birthdate = Carbon::parse($value);
            $maxAgeDate = Carbon::now()->subYears(12);
            $minAgeDate = Carbon::now();
            
            if ($birthdate->lt($maxAgeDate)) {
                $fail('Usia anak maksimal adalah 12 tahun.');
            }
            
            if ($birthdate->gt($minAgeDate)) {
                $fail('Tanggal lahir tidak boleh di masa depan.');
            }
        }];
        $rules['size_anak'] = 'required|string|in:XS,S,M,L,XL,XXL';
    }

    if ($category->name === 'Fun Run') {
        $rules['jarak_lari'] = 'required|string|in:3K,7K';
    }

    if ($category->name === 'Family Run') {
        $rules['nama_anak'] = 'required|string|max:255';
        $rules['tgl_lahir_anak'] = 'required|date';
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

        // User creation
        $user = new User;
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->no_hp = $request->no_hp;
        $user->nik = $request->nik;
        $user->gender = $request->gender;

        // Menangani tanggal lahir dan tanggal lahir anak untuk semua kategori
        if ($request->has('tgl_lahir')) {
            $user->tgl_lahir = Carbon::parse($request->tgl_lahir)->format('Y-m-d');
        }
        
        if ($request->has('tgl_lahir_anak')) {
            Log::info('Mencoba menyimpan tgl_lahir_anak: ' . $request->tgl_lahir_anak);
            $user->tgl_lahir_anak = Carbon::parse($request->tgl_lahir_anak)->format('Y-m-d');
        }

        $user->gol_darah = $request->gol_darah;
        $user->alamat = $request->alamat;
        $user->komunitas = $request->komunitas;
        $user->kontak_darurat_name = $request->kontak_darurat_name;
        $user->kontak_darurat_no = $request->kontak_darurat_no;
        $user->save();
        
        Log::info('User berhasil disimpan dengan ID: ' . $user->id);
        
        // Log data yang disimpan
        Log::info('Data user tersimpan: ', $user->toArray());

        $totalPrice = $category->price;

        // Generate order number
        $today = Carbon::now()->format('Ymd');
        $prefix = 'RUN-' . $today . '-';

        // Cache lock for atomicity
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

        // Create new order
        $orderData = [
            'user_id' => $user->id,
            'ticket_category_id' => $category->id,
            'order_number' => $orderNumber,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'payment_deadline' => null, // Set payment_deadline ke null
            'bib_name' => $request->bib_name,
            'gender' => $request->gender,
            'nik' => $request->nik,
            'gol_darah' => $request->gol_darah,
            'alamat' => $request->alamat,
            'komunitas' => $request->komunitas,
            'kontak_darurat_name' => $request->kontak_darurat_name,
            'kontak_darurat_no' => $request->kontak_darurat_no
        ];

        // Tambahkan size_chart untuk semua kategori yang membutuhkan
        if ($category->name === 'Fun Run' || $category->name === 'Family Run' || $category->name === 'Early Bird - Fun Run 7K') {
            $orderData['size_chart'] = $request->size_chart;
        }

        // Tambahkan data khusus per kategori
        if ($category->name === 'Fun Run' || $category->name === 'Early Bird - Fun Run 7K') {
            if ($category->name === 'Fun Run') {
                $orderData['jarak_lari'] = $request->jarak_lari;
            }
        } elseif ($category->name === 'Family Run') {
            $orderData['nama_anak'] = $request->nama_anak;
            $orderData['tgl_lahir_anak'] = Carbon::parse($request->tgl_lahir_anak)->format('Y-m-d');
            $orderData['size_anak'] = $request->size_anak;
            $orderData['bib_anak'] = $request->bib_anak;
        } elseif ($category->name === 'Kids 3K') {
            $orderData['size_anak'] = $request->size_anak;
            $orderData['tgl_lahir_anak'] = Carbon::parse($request->tgl_lahir_anak)->format('Y-m-d');
        }

        $order = Order::create($orderData);
        Log::info('Order berhasil dibuat dengan ID: ' . $order->id);

        // Create payment record
        $payment = Payment::create([
            'order_id' => $order->id,
            'status' => 'pending',
            'amount' => $totalPrice,
        ]);

        DB::commit();

        // Hanya kirim email pengingatan, tanpa job untuk expired
        SendPaymentReminderEmail::dispatch($order)->delay(Carbon::now()->addMinutes(0));

        return redirect()->route('orders.payment', $order->id);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error dalam proses store: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
    }
}

    
    public function showPayment($orderId)
{
    try {
        $order = Order::with(['ticketCategory', 'payment', 'user', 'addOns'])->findOrFail($orderId);
        
        // Harga dasar tiket (tanpa admin fee dan kode unik)
        $basePrice = $order->ticketCategory->price;
        
        // Tambahkan add-ons jika ada
        if($order->addOns->count() > 0) {
            foreach($order->addOns as $addon) {
                $basePrice += $addon->price;
            }
        }
        
        // Kurangi diskon voucher jika ada
        $orderVoucher = OrderVoucher::where('order_id', $order->id)->first();
        $voucher = null;
        if ($orderVoucher) {
            $voucher = Voucher::find($orderVoucher->voucher_id);
            if ($voucher) {
                $basePrice -= $voucher->discount_amount;
                // Pastikan tidak negatif
                $basePrice = max(0, $basePrice);
            }
        }
        
        // Biaya admin
        $adminFee = 4000;
        
        // Generate kode unik 3 digit (konsisten untuk order yang sama)
        $uniqueCode = ($order->id * 7 + 101) % 900 + 100;
        
        // Total pembayaran termasuk biaya admin dan kode unik
        $totalPayment = $basePrice + $adminFee + $uniqueCode;
        
        // Update total_price di order dengan total payment
        $order->total_price = $totalPayment;
        $order->save();
        
        if (!$order->payment) {
            Log::warning('Payment record not found for order #' . $order->id . ', creating one');
            Payment::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'amount' => $totalPayment,
            ]);
            
            $order = Order::with(['ticketCategory', 'payment', 'user', 'addOns'])->findOrFail($orderId);
        } else {
            // Update payment amount
            $order->payment->amount = $totalPayment;
            $order->payment->save();
        }
        
        if ($order->status !== 'pending') {
            return redirect()->route('home')->with('error', 'Order ini tidak dalam status pending.');
        }
        
        // Kirim komponen pembayaran terpisah ke view
        return view('orders.payment', [
            'order' => $order,
            'voucher' => $voucher,
            'basePrice' => $basePrice,
            'adminFee' => $adminFee,
            'uniqueCode' => $uniqueCode
        ]);
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
            
            // Cek apakah voucher berlaku untuk kategori tiket pesanan
            if ($voucher->ticket_category_id != $order->ticketCategory->id) {
                throw new \Exception('Voucher tidak dapat diterapkan pada kategori tiket ini.');
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
