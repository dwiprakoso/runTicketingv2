<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TicketCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'quota', 'description', 'visibility', 'expiration_date'];

    /**
     * Generate a unique private token for the ticket category.
     */
    // protected $fillable = ['name', 'price', 'quota', 'description', 'visibility', 'private_token', 'expiration_date'];

// Tambahkan method untuk memeriksa apakah kategori adalah private
    public function isPrivate()
    {
        return $this->visibility === 'private';
    }

    // Tambahkan method untuk memeriksa apakah kategori adalah public
    public function isPublic()
    {
        return $this->visibility === 'public';
    }

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
