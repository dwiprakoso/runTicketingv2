<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ticket_category_id',
        'order_number', // Tambahkan ini
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
    
    public static function generateOrderNumber(): string
    {
        $today = Carbon::now()->format('Ymd');
        $prefix = 'RUN-' . $today . '-';
        
        return Cache::lock('order_number_lock', 10)->get(function () use ($prefix) {
            $lastOrder = self::where('order_number', 'like', $prefix . '%')
                ->orderBy('id', 'desc')
                ->first();
                
            if ($lastOrder) {
                $lastNumber = substr($lastOrder->order_number, strlen($prefix));
                $newNumber = (int)$lastNumber + 1;
                return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
            }
            
            return $prefix . '0001';
        });
    }
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