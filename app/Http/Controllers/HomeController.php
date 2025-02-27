<?php
// app/Http/Controllers/HomeController.php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\TicketCategory;

class HomeController extends Controller
{
    public function index()
    {
        $categories = TicketCategory::all();
        
        return view('home.index', compact('categories'));
    }
    public function checkOrder(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string'
        ]);
        
        $order = Order::where('order_number', $request->order_number)->first();
        
        if (!$order) {
            return redirect()->route('home')->with('error', 'Pesanan tidak ditemukan.');
        }
        
        return redirect()->route('orders.success', $order->id);
    }
}