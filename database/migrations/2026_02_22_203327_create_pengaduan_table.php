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
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_tiket')->unique();
            $table->integer('kategori_pengaduan_id'); // Match int(11) of kategori_pengaduan
            $table->string('judul');
            $table->text('deskripsi');
            $table->foreignId('gedung_id')->constrained('gedung');
            $table->foreignId('ruang_id')->constrained('ruang');
            $table->text('eviden_path')->nullable()->comment('JSON array of paths');
            $table->enum('status', ['1', '2', '3', '4', '5', '6'])->default('1');
            $table->text('tidan_lanjut_path')->nullable()->comment('JSON array of paths');
            $table->text('catatan_tindak_lanjut')->nullable();
            $table->text('catatan_eskalasi')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->foreignId('escalated_to')->nullable()->constrained('users');
            $table->dateTime('tanggal_selesai')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Explicit foreign key for kategori_pengaduan due to type mismatch (int vs bigint)
            $table->foreign('kategori_pengaduan_id')->references('id')->on('kategori_pengaduan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};
