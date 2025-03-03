<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ticket_categories', function (Blueprint $table) {
            $table->enum('visibility', ['public', 'private'])->default('public')->after('description');
        });
    }

    public function down()
    {
        Schema::table('ticket_categories', function (Blueprint $table) {
            $table->dropColumn('visibility');
        });
    }
};