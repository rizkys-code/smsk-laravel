@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('surat-keluar') }}">Surat Keluar</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Review</li>
                    </ol>
                </nav>
                <h2 class="fw-bold text-primary mb-0">Review Surat Keluar</h2>
            </div>
            <a href="{{ route('surat-keluar') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>{{ session('success') }}</strong>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <!-- Detail Surat -->
                <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-file-earmark-text me-2"></i>Detail Surat
                        </h5>
                        <span
                            class="badge
                        {{ $surat->status === 'disetujui' ? 'bg-success' : ($surat->status === 'ditolak' ? 'bg-danger' : 'bg-warning') }}
                        d-flex align-items-center gap-1 px-3 py-2">
                            <i
                                class="bi {{ $surat->status === 'disetujui' ? 'bi-check-circle' : ($surat->status === 'ditolak' ? 'bi-x-circle' : 'bi-hourglass-split') }}"></i>
                            {{ ucfirst($surat->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex flex-column">
                                    <span class="text-muted small">Nomor Surat</span>
                                    <span class="fw-medium fs-5">{{ $surat->nomor_surat }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex flex-column">
                                    <span class="text-muted small">Tanggal</span>
                                    <span
                                        class="fw-medium">{{ \Carbon\Carbon::parse($surat->tanggal)->format('d F Y') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex flex-column">
                                    <span class="text-muted small">Jenis Surat</span>
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
                                    <span class="fw-medium">{{ $kodeSurat[$surat->jenis] ?? $surat->jenis }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex flex-column">
                                    <span class="text-muted small">Perihal</span>
                                    <span class="fw-medium">{{ $surat->perihal }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex flex-column">
                                    <span class="text-muted small mb-2">Isi Surat</span>
                                    <div class="border rounded p-3 bg-light">
                                        {!! nl2br(e($surat->isi)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lampiran Tambahan -->
                @if ($lampiran->count())
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold text-primary">
                                <i class="bi bi-paperclip me-2"></i>Lampiran Tambahan
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0">Label</th>
                                            <th class="border-0">Isi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lampiran as $item)
                                            <tr>
                                                <td class="fw-medium">{{ $item->label }}</td>
                                                <td>{{ $item->isi }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Komentar Revisi -->
                @if ($surat->komentarRevisi->count())
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold text-primary">
                                <i class="bi bi-chat-left-text me-2"></i>Komentar Revisi
                                <span
                                    class="badge bg-primary rounded-pill ms-2">{{ $surat->komentarRevisi->count() }}</span>
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach ($surat->komentarRevisi as $komentar)
                                    <div class="list-group-item px-4 py-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-circle bg-primary text-white">
                                                    {{ substr($komentar->user->name, 0, 1) }}
                                                </div>
                                                <span class="fw-bold">{{ $komentar->user->name }}</span>
                                            </div>
                                            <small class="text-muted d-flex align-items-center gap-1">
                                                <i class="bi bi-clock"></i>
                                                {{ $komentar->created_at->format('d M Y, H:i') }}
                                            </small>
                                        </div>
                                        <div class="mb-2">{{ $komentar->komentar }}</div>
                                        @if ($komentar->dokumen_revisi_path)
                                            <a href="{{ Storage::url($komentar->dokumen_revisi_path) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-file-earmark-text"></i>
                                                Lihat Dokumen Revisi
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Status Surat</h5>

                        <!-- Timeline -->
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">Dibuat</h6>
                                    <small
                                        class="text-muted">{{ \Carbon\Carbon::parse($surat->created_at)->format('d M Y, H:i') }}</small>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div
                                    class="timeline-marker {{ $surat->status !== 'menunggu' ? 'bg-success' : 'bg-secondary' }}">
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">Diajukan</h6>
                                    <small
                                        class="text-muted">{{ \Carbon\Carbon::parse($surat->updated_at)->format('d M Y, H:i') }}</small>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div
                                    class="timeline-marker
                                {{ $surat->status === 'disetujui'
                                    ? 'bg-success'
                                    : ($surat->status === 'ditolak'
                                        ? 'bg-danger'
                                        : 'bg-secondary') }}">
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">
                                        {{ $surat->status === 'disetujui'
                                            ? 'Disetujui'
                                            : ($surat->status === 'ditolak'
                                                ? 'Ditolak'
                                                : 'Menunggu Persetujuan') }}
                                    </h6>
                                    @if ($surat->status !== 'menunggu')
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($surat->updated_at)->format('d M Y, H:i') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Persetujuan / Penolakan -->
                        @if ($surat->status === 'menunggu')
                            <div class="mt-4">
                                <h6 class="fw-bold mb-3">Tindakan</h6>
                                <form action="{{ route('surat-keluar.approval', $surat->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="d-grid gap-2">
                                        <button type="submit" name="status" value="disetujui"
                                            class="btn btn-success d-flex align-items-center justify-content-center gap-2"
                                            onclick="return confirm('Yakin ingin menyetujui surat ini?')">
                                            <i class="bi bi-check-circle"></i> Setujui Surat
                                        </button>
                                        <button type="submit" name="status" value="ditolak"
                                            class="btn btn-danger d-flex align-items-center justify-content-center gap-2"
                                            onclick="return confirm('Yakin ingin menolak surat ini?')">
                                            <i class="bi bi-x-circle"></i> Tolak Surat
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-{{ $surat->status === 'disetujui' ? 'success' : 'danger' }} d-flex align-items-center mt-4"
                                role="alert">
                                <i
                                    class="bi {{ $surat->status === 'disetujui' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-2"></i>
                                <div>
                                    Surat ini telah <strong>{{ $surat->status }}</strong>.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Form Tambah Komentar -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-chat-right-dots me-2"></i>Tambah Komentar
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('surat-keluar.komentar', $surat->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="komentar" class="form-label fw-medium">Komentar</label>
                                <textarea name="komentar" id="komentar" rows="4" class="form-control"
                                    placeholder="Tulis komentar atau catatan revisi di sini..." required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="dokumen_revisi" class="form-label fw-medium">
                                    <i class="bi bi-paperclip me-1"></i>Upload Dokumen Revisi
                                </label>
                                <input type="file" name="dokumen_revisi" id="dokumen_revisi" class="form-control">
                                <div class="form-text">Format yang didukung: PDF, DOC, DOCX (Maks. 5MB)</div>
                            </div>
                            <div class="d-grid">
                                <button type="submit"
                                    class="btn btn-primary d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-send"></i> Kirim Komentar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 9px;
            width: 2px;
            background-color: #e9ecef;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -30px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 0 0 2px #e9ecef;
        }

        .timeline-content {
            padding-bottom: 10px;
        }

        .timeline-item:last-child .timeline-content {
            padding-bottom: 0;
        }
    </style>
@endsection
