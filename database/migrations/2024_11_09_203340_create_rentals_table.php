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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supir_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('pelanggan_id')->constrained()->cascadeOnDelete();
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali');
            $table->json('perlengkapan')->nullable();
            $table->decimal('harga', 11, 2)->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
