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
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi dengan tabel users
            $table->string('jenis_surat'); // Jenis surat (misal: undangan, sertifikat)
            $table->string('nama_instansi'); // Nama instansi/perorangan pengirim surat
            $table->string('nama_surat'); // Nama surat
            $table->binary('foto_dokumen'); // Dokumen surat dalam bentuk file (bisa berupa foto, pdf, dll)
            $table->timestamps(); // Menyimpan waktu pembuatan dan pembaruan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};
