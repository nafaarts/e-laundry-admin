<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('laundry_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['PESANAN_DIBUAT', 'DITERIMA', 'DICUCI', 'SETRIKA', 'SELESAI', 'DIBATALKAN'])->default('PESANAN_DIBUAT');
            $table->boolean('is_paid')->default(false);
            $table->boolean('is_pickedup')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
