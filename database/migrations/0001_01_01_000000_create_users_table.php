<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('gender', ['laki-laki', 'perempuan']);
            $table->date('tgl_lahir'); // Birth date
            $table->string('email')->unique();
            $table->string('no_hp'); // Phone number
            $table->string('nik'); // National ID
            $table->enum('gol_darah', ['A', 'B', 'AB', 'O']); // Blood type
            $table->text('alamat'); // Address
            $table->string('komunitas')->nullable(); // Community
            $table->string('kontak_darurat_name'); // Emergency contact name
            $table->string('kontak_darurat_no'); // Emergency contact number
            $table->timestamps();
        });
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
