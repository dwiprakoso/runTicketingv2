<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class TicketCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ticket_categories')->insert([
            [
                'name' => 'Umum',
                'price' => 250000.00,
                'quota' => 50,
                'description' => 'Tiket Umum dengan fasilitas eksklusif.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Family Run',
                'price' => 450000.00,
                'quota' => 200,
                'description' => 'Tiket Regular dengan akses standar.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Kids',
                'price' => 200000.00,
                'quota' => 100,
                'description' => 'Tiket khusus Early Bird dengan harga lebih murah.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
