<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomentarRevisi extends Model
{
    protected $table = 'komentar_revisi';
    protected $fillable = ['surat_id', 'komentar', 'created_by', 'dokumen_revisi_path'];

    public function surat()
    {
        return $this->belongsTo(SuratKeluar::class, 'surat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

