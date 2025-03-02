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
            // ['name' => 'Fun Run', 'description' => 'Terdapat pilihan jarak 3K dan 7K', 'price' => 250000, 'quota' => 50],
            // ['name' => 'Family Run', 'description' => 'Berlari bersama keluarga (1 Dewasa & 1 Anak - Anak)', 'price' => 450000, 'quota' => 200],
            // ['name' => 'Kids 3K', 'description' => 'Daftarkan anak anda dengan maksimal usia 12 Tahun untuk berlari 3K', 'price' => 200000, 'quota' => 100],
            ['name' => 'Early Bird - Fun Run 7K', 'description' => 'Tiket Early Bird untuk kategori Fun Run 7K dengan Kuota Terbatas.', 'price' => 200000, 'quota' => 75],
        ]);
    }
}
