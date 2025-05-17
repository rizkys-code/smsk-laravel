<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\LampiranSurat;
use App\Models\KomentarRevisi;

class SuratKeluar extends Model
{
    protected $table = 'surat_keluar';

    protected $fillable = [
        'nomor_surat',
        'perihal',
        'isi',
        'tanggal',
        'jenis',
        'status',
        'user_id',
        'signed_by',
        'signed_at',
        'barcode_path',
    ];

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    public function lampiran()
    {
        return $this->hasMany(LampiranSurat::class, 'surat_id');
    }

    public function komentarRevisi()
    {
        return $this->hasMany(KomentarRevisi::class, 'surat_id');
    }
    public function revisi()
    {
        return $this->hasMany(SuratRevisi::class, 'surat_id');
    }
}
