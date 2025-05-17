@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('surat-revisi') }}">Revisi Surat</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Revisi</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-primary mb-0">Revisi Surat</h2>
            <p class="text-muted">Perbaiki data surat yang ditolak</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('surat-revisi') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Alert for Revision Comments -->
    @if ($surat->komentar_revisi)
    <div class="alert alert-info d-flex" role="alert">
        <div class="flex-shrink-0">
            <i class="bi bi-info-circle-fill fs-4 me-2"></i>
        </div>
        <div>
            <h5 class="alert-heading">Komentar Revisi</h5>
            <p class="mb-0">{{ $surat->komentar_revisi }}</p>
        </div>
    </div>
    @endif

    <!-- Form Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="bi bi-pencil-square me-2"></i>Form Revisi Data Surat
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('surat-revisi.update', $surat->id) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label for="jenis_surat" class="form-label fw-medium">Jenis Surat</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-envelope-paper"></i>
                        </span>
                        <select id="jenis_surat" name="jenis_surat" class="form-select" required>
                            <option value="">-- Pilih Jenis Surat --</option>
                            <option value="SA" {{ $surat->jenis == 'SA' ? 'selected' : '' }}>Sertif Asisten / PKL</option>
                            <option value="SS" {{ $surat->jenis == 'SS' ? 'selected' : '' }}>Sertif Webinar / Workshop / Media Partner</option>
                            <option value="H" {{ $surat->jenis == 'H' ? 'selected' : '' }}>Surat Perbaikan</option>
                            <option value="P" {{ $surat->jenis == 'P' ? 'selected' : '' }}>Formulir Pendaftaran Calas</option>
                            <option value="S" {{ $surat->jenis == 'S' ? 'selected' : '' }}>SK Asisten / Keterangan</option>
                            <option value="U" {{ $surat->jenis == 'U' ? 'selected' : '' }}>Surat Undangan</option>
                            <option value="K" {{ $surat->jenis == 'K' ? 'selected' : '' }}>Surat Keputusan</option>
                        </select>
                    </div>
                    @error('jenis_surat')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="perihal" class="form-label fw-medium">Perihal Surat</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-chat-square-text"></i>
                        </span>
                        <input type="text" name="perihal" id="perihal" class="form-control" value="{{ old('perihal', $surat->perihal) }}" placeholder="Perihal Surat" required>
                    </div>
                    @error('perihal')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="nomor_surat" class="form-label fw-medium">Nomor Surat</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-hash"></i>
                        </span>
                        <input type="text" name="nomor_surat" id="nomor_surat" class="form-control" value="{{ $surat->nomor_surat }}" readonly>
                    </div>
                    <div class="form-text">Nomor surat tidak dapat diubah</div>
                </div>

                <div class="col-md-6">
                    <label for="tanggal" class="form-label fw-medium">Tanggal Surat</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-calendar-date"></i>
                        </span>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', $surat->tanggal) }}" required>
                    </div>
                    @error('tanggal')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="isi_surat" class="form-label fw-medium">Isi Surat</label>
                    <textarea name="isi_surat" id="isi_surat" rows="8" class="form-control" required>{{ old('isi_surat', $surat->isi) }}</textarea>
                    @error('isi_surat')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="keterangan_revisi" class="form-label fw-medium">Keterangan Revisi</label>
                    <textarea name="keterangan_revisi" id="keterangan_revisi" rows="3" class="form-control" placeholder="Jelaskan perubahan yang dilakukan...">{{ old('keterangan_revisi') }}</textarea>
                    <div class="form-text">Berikan penjelasan tentang perubahan yang telah dilakukan</div>
                </div>

                <div class="col-12 mt-4">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('surat-revisi') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Original Data Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="bi bi-file-earmark-text me-2"></i>Data Surat Asli
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="bi bi-envelope-paper text-primary"></i>
                            <span>Jenis Surat</span>
                        </div>
                        <div class="detail-value">
                            @php
                                $kodeSurat = [
                                    'SA' => 'Sertif Asisten / PKL',
                                    'SS' => 'Sertif Webinar / Workshop / Media Partner',
                                    'H' => 'Surat Perbaikan',
                                    'P' => 'Formulir Pendaftaran Calas',
                                    'S' => 'SK Asisten / Keterangan',
                                    'U' => 'Surat Undangan',
                                    'K' => 'Surat Keputusan',
                                ];
                            @endphp
                            {{ $kodeSurat[$surat->jenis] ?? $surat->jenis }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="bi bi-chat-square-text text-primary"></i>
                            <span>Perihal</span>
                        </div>
                        <div class="detail-value">{{ $surat->perihal }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="bi bi-hash text-primary"></i>
                            <span>Nomor Surat</span>
                        </div>
                        <div class="detail-value">{{ $surat->nomor_surat }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="bi bi-calendar-date text-primary"></i>
                            <span>Tanggal</span>
                        </div>
                        <div class="detail-value">{{ \Carbon\Carbon::parse($surat->tanggal)->format('d F Y') }}</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="bi bi-file-text text-primary"></i>
                            <span>Isi Surat</span>
                        </div>
                        <div class="detail-value content-box">
                            {!! nl2br(e($surat->isi)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Detail Item Styling */
    .detail-item {
        margin-bottom: 0.5rem;
    }

    .detail-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
    }

    .detail-value {
        font-weight: 500;
        font-size: 1rem;
    }

    .content-box {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 0.375rem;
        padding: 1rem;
        white-space: pre-line;
    }
</style>
@endsection
