<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ticket_category_id')->constrained('ticket_categories')->onDelete('cascade');
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'verified', 'rejected', 'expired'])->default('pending');
            $table->timestamp('payment_deadline')->nullable();
            $table->string('bib_name'); // Name to display on race bib
            
            // Fields for different categories
            $table->string('size_chart')->nullable();; // T-shirt size
            $table->string('jarak_lari')->nullable(); // Race distance (for 'Umum' category)
            $table->string('nama_anak')->nullable(); // Child's name (for 'Family Run' category)
            $table->string('usia_anak')->nullable(); // Child's age (for 'Family Run' category)
            $table->string('size_anak')->nullable(); // Child's t-shirt size (for 'Family Run' category)
            $table->string('bib_anak')->nullable(); // Child's bib name (for 'Family Run' category)
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};