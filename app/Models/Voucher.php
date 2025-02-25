<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_amount',
        'quota',
    ];

    public function orderVouchers()
    {
        return $this->hasMany(OrderVoucher::class);
    }
    
    public function availableQuota()
    {
        $usedCount = $this->orderVouchers()
                         ->whereHas('order', function($query) {
                             $query->whereIn('status', ['pending', 'verified']);
                         })
                         ->count();
                         
        return $this->quota - $usedCount;
    }
}
