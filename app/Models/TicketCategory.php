<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'quota',
        'description',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function availableQuota()
    {
        $soldCount = $this->orders()
                        ->whereIn('status', ['pending', 'verified'])
                        ->count();
        
        return $this->quota - $soldCount;
    }
}
