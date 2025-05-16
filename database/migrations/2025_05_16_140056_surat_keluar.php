<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->string('perihal');
            $table->text('isi');
            $table->date('tanggal');
            $table->string('jenis');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'diperbaiki', 'dicetak'])->default('menunggu');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('signed_by')->nullable()->constrained('users');
            $table->timestamp('signed_at')->nullable();
            $table->string('barcode_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keluar');
    }
};
