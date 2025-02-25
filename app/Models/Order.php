<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ticket_category_id',
        'total_price',
        'status',
        'payment_deadline',
        'size_chart',
        'bib_name',
        'jarak_lari',
        'nama_anak',
        'usia_anak',
        'size_anak',
        'bib_anak',
    ];

    protected $casts = [
        'payment_deadline' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticketCategory()
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function addOns()
    {
        return $this->hasMany(AddOn::class);
    }
    
    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'order_vouchers');
    }
    
    public function orderVoucher()
    {
        return $this->hasOne(OrderVoucher::class);
    }
}