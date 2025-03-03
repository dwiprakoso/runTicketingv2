<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\AddOn;
use App\Models\Order;
use App\Models\Voucher;
use App\Mail\OrderRejected;
use Illuminate\Http\Request;
use App\Jobs\SendTicketEmail;
use App\Models\TicketCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    // Admin secret key for authentication 
    private $adminSecretKey = 'admin-secret-key-123'; // Anda bisa mengubah ini atau menempatkannya di .env
    
    public function showLogin()
    {
        return view('admin.login');
    }
    
    public function login(Request $request)
    {
        if ($request->secret_key === $this->adminSecretKey) {
            session(['admin_authenticated' => true]);
            return redirect()->route('admin.dashboard');
        }
        
        return redirect()->back()->with('error', 'Kunci rahasia tidak valid.');
    }
    
    public function dashboard(Request $request)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }

        $query = Order::with([
            'user', 'ticketCategory', 'payment', 'addOns', 'orderVoucher.voucher'
        ]);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('ticketCategory')) {
            $query->whereHas('ticketCategory', function ($q) use ($request) {
                $q->where('name', $request->ticketCategory);
            });
        }

        $orders = $query->paginate(10);
        $categories = TicketCategory::all();
        $vouchers = Voucher::all();
        
        // Calculate statistics for all orders per category
        $categoryStats = [];
        foreach ($categories as $category) {
            $count = Order::whereHas('ticketCategory', function($q) use ($category) {
                $q->where('id', $category->id);
            })->count();
            $categoryStats[$category->name] = $count;
        }
        
        // Calculate statistics for VERIFIED orders per category
        $verifiedCategoryStats = [];
        foreach ($categories as $category) {
            $count = Order::where('status', 'verified')
                ->whereHas('ticketCategory', function($q) use ($category) {
                    $q->where('id', $category->id);
                })->count();
            $verifiedCategoryStats[$category->name] = $count;
        }
        
        // Modified: Calculate gender stats ONLY for verified orders
        $genderStats = [
            'Laki-laki' => Order::where('status', 'verified')
                ->whereHas('user', function($q) {
                    $q->where('gender', 'laki-laki');
                })->count(),
            'Perempuan' => Order::where('status', 'verified')
                ->whereHas('user', function($q) {
                    $q->where('gender', 'perempuan');
                })->count()
        ];
        
        // Modified: Add "Other" category for verified orders if needed
        $otherGenderCount = Order::where('status', 'verified')
            ->whereHas('user', function($q) {
                $q->whereNotIn('gender', ['laki-laki', 'perempuan']);
            })->count();
        
        if ($otherGenderCount > 0) {
            $genderStats['Lainnya'] = $otherGenderCount;
        }
        
        // Calculate combined shirt size statistics ONLY FOR VERIFIED ORDERS
        $sizesArr = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL'];
        $sizeStats = [];
        
        // Initialize the size statistics array
        foreach ($sizesArr as $size) {
            $sizeStats[$size] = 0;
        }
        
        // Count size_chart occurrences (verified only)
        $sizeChartCounts = Order::where('status', 'verified')
            ->whereNotNull('size_chart')
            ->select('size_chart', DB::raw('count(*) as total'))
            ->groupBy('size_chart')
            ->pluck('total', 'size_chart')
            ->toArray();
        
        // Count size_anak occurrences (verified only)
        $sizeAnakCounts = Order::where('status', 'verified')
            ->whereNotNull('size_anak')
            ->select('size_anak', DB::raw('count(*) as total'))
            ->groupBy('size_anak')
            ->pluck('total', 'size_anak')
            ->toArray();
        
        // Combine the counts
        foreach ($sizeChartCounts as $size => $count) {
            if (in_array($size, $sizesArr)) {
                $sizeStats[$size] += $count;
            } else {
                if (!isset($sizeStats['Lainnya'])) {
                    $sizeStats['Lainnya'] = 0;
                }
                $sizeStats['Lainnya'] += $count;
            }
        }
        
        foreach ($sizeAnakCounts as $size => $count) {
            if (in_array($size, $sizesArr)) {
                $sizeStats[$size] += $count;
            } else {
                if (!isset($sizeStats['Lainnya'])) {
                    $sizeStats['Lainnya'] = 0;
                }
                $sizeStats['Lainnya'] += $count;
            }
        }
        
        // Remove sizes with zero count
        foreach ($sizeStats as $size => $count) {
            if ($count == 0) {
                unset($sizeStats[$size]);
            }
        }
        
        // We've combined both sets of statistics for verified orders only,
        // so we'll just use the same $sizeStats for both variables
        $verifiedSizeStats = $sizeStats;

        return view('admin.dashboard', compact(
            'orders', 
            'categories', 
            'vouchers', 
            'categoryStats', 
            'verifiedCategoryStats', 
            'genderStats', 
            'sizeStats',
            'verifiedSizeStats'
        ));
    }
    public function verifyOrder($orderId)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }

        $order = Order::with(['user', 'payment'])->findOrFail($orderId);
        
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Order tidak dapat diverifikasi.');
        }
        
        if (!($order->payment->amount == 0 && !$order->payment->proof_image) && 
            (!$order->payment || !$order->payment->proof_image)) {
            return redirect()->back()->with('error', 'Order tidak dapat diverifikasi karena bukti pembayaran tidak ada.');
        }

        $order->status = 'verified';
        $order->save();

        $order->payment->status = 'verified';
        $order->payment->save();

        SendTicketEmail::dispatch($order);

        return redirect()->back()->with('success', 'Order berhasil diverifikasi dan tiket telah dikirim.');
    }

    public function rejectOrder($orderId)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }

        $order = Order::with(['user', 'payment'])->findOrFail($orderId);

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Order tidak dapat ditolak.');
        }

        $order->status = 'rejected';
        $order->save();

        $order->payment->status = 'rejected';
        $order->payment->save();

        Mail::to($order->user->email)->send(new OrderRejected($order));

        return redirect()->back()->with('success', 'Order berhasil ditolak dan email pemberitahuan telah dikirim.');
    }

    
    public function updateTicketCategory(Request $request, $categoryId)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Unauthorized');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        try {
            $category = TicketCategory::findOrFail($categoryId);
            $category->update($request->all());
            
            return redirect()->route('admin.dashboard')->with('success', 'Kategori tiket berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function createTicketCategory(Request $request)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        TicketCategory::create($request->all());
        
        return redirect()->back()->with('success', 'Kategori tiket baru berhasil dibuat.');
    }
    
    public function createVoucher(Request $request)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }
        
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:vouchers',
            'discount_amount' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:1',
            'ticket_category_id' => 'required|exists:ticket_categories,id',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        Voucher::create($request->all());
        
        return redirect()->back()->with('success', 'Voucher baru berhasil dibuat.');
    }
    
    // New method for updating vouchers
    public function updateVoucher(Request $request, $voucherId)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }
        
        $voucher = Voucher::findOrFail($voucherId);
        
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:vouchers,code,' . $voucherId,
            'discount_amount' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:1',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $voucher->update($request->all());
        
        return redirect()->back()->with('success', 'Voucher berhasil diperbarui.');
    }
    
    // New method for deleting vouchers
    public function deleteVoucher($voucherId)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }
        
        $voucher = Voucher::findOrFail($voucherId);
        
        // Check if the voucher has been used
        $hasBeenUsed = $voucher->orderVouchers()->exists();
        
        if ($hasBeenUsed) {
            return redirect()->back()->with('error', 'Voucher tidak dapat dihapus karena telah digunakan.');
        }
        
        $voucher->delete();
        
        return redirect()->back()->with('success', 'Voucher berhasil dihapus.');
    }
    
    public function deleteTicketCategory($categoryId)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }
        
        $category = TicketCategory::findOrFail($categoryId);
        
        // Check if the category has associated orders
        $hasOrders = $category->orders()->exists();
        
        if ($hasOrders) {
            return redirect()->back()->with('error', 'Kategori tidak dapat dihapus karena sudah memiliki pesanan terkait.');
        }
        
        $category->delete();
        
        return redirect()->back()->with('success', 'Kategori tiket berhasil dihapus.');
    }
}