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
    
    public function dashboard()
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }

        // Ambil semua pesanan dengan relasi yang diperlukan
        $orders = Order::with([
            'user', 
            'ticketCategory', 
            'payment', 
            'addOns', 
            'orderVoucher.voucher'
        ])->get();
        // Ambil semua kategori tiket dan voucher
        $categories = TicketCategory::all();
        $vouchers = Voucher::all();

        return view('admin.dashboard', compact('orders', 'categories', 'vouchers'));
    }

    
    public function verifyOrder($orderId)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }

        // Memuat data pesanan beserta informasi pembayaran
        $order = Order::with(['user', 'payment'])->findOrFail($orderId);
        
        // Pastikan status pesanan adalah 'pending'
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Order tidak dapat diverifikasi.');
        }
        
        // Periksa jika jumlah pembayaran adalah 0 (gratis), izinkan verifikasi meskipun tanpa bukti pembayaran
        if (!($order->payment->amount == 0 && !$order->payment->proof_image) && 
            (!$order->payment || !$order->payment->proof_image)) {
            return redirect()->back()->with('error', 'Order tidak dapat diverifikasi karena bukti pembayaran tidak ada.');
        }

        // Mengubah status pesanan menjadi 'verified'
        $order->status = 'verified';
        $order->save();

        // Mengubah status pembayaran menjadi 'verified'
        $order->payment->status = 'verified';
        $order->payment->save();

        // Kirim tiket melalui email
        SendTicketEmail::dispatch($order);

        return redirect()->back()->with('success', 'Order berhasil diverifikasi dan tiket telah dikirim.');
    }

    public function rejectOrder($orderId)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }

        // Memuat data pesanan beserta informasi pembayaran
        $order = Order::with(['user', 'payment'])->findOrFail($orderId);

        // Pastikan status pesanan adalah 'pending'
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Order tidak dapat ditolak.');
        }

        // Mengubah status pesanan menjadi 'rejected'
        $order->status = 'rejected';
        $order->save();

        // Mengubah status pembayaran menjadi 'rejected'
        $order->payment->status = 'rejected';
        $order->payment->save();

        // Kirim email penolakan pesanan
        Mail::to($order->user->email)->send(new OrderRejected($order));

        return redirect()->back()->with('success', 'Order berhasil ditolak dan email pemberitahuan telah dikirim.');
    }

    
    public function updateTicketCategory(Request $request, $categoryId)
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
        
        $category = TicketCategory::findOrFail($categoryId);
        $category->update($request->all());
        
        return redirect()->back()->with('success', 'Kategori tiket berhasil diperbarui.');
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
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        Voucher::create($request->all());
        
        return redirect()->back()->with('success', 'Voucher baru berhasil dibuat.');
    }
}