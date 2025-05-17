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
        Schema::create('surat_revisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surat_keluar')->onDelete('cascade');
            $table->text('komentar_revisi');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_revisi');
    }
};
