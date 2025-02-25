<?php
// app/Http/Controllers/HomeController.php
namespace App\Http\Controllers;

use App\Models\TicketCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = TicketCategory::all();
        
        return view('home.index', compact('categories'));
    }
}