<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'proof_image',
        'status',
        'amount',  // Add this if you're using it
        'payment_method',  // Add this if you're using it
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}