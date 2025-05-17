@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Revisi Surat</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-primary mb-0">Menu Revisi Surat</h2>
            <p class="text-muted">Kelola dan perbaiki surat yang memerlukan revisi</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('surat-keluar') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i> Kembali ke Surat Keluar
            </a>
        </div>
    </div>

    <!-- Status Alert -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong>{{ session('success') }}</strong>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Daftar Surat untuk Revisi -->
    <div class="card shadow-sm border-0 mb-4 overflow-hidden">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-pencil-square me-2"></i>Surat yang Perlu Direvisi
                </h5>
                <div class="input-group" style="width: 250px;">
                    <input type="text" class="form-control" placeholder="Cari surat..." id="searchInput">
                    <span class="input-group-text bg-primary text-white">
                        <i class="bi bi-search"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="revisiTable">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Nomor Surat</th>
                            <th class="py-3">Perihal</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Komentar Revisi</th>
                            <th class="py-3">Dokumen Revisi</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($revisiList as $surat)
                        <tr>
                            <td class="px-4 fw-medium">{{ $surat->nomor_surat }}</td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                    {{ $surat->perihal ?? 'Tidak ada perihal' }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($surat->tanggal)->format('d M Y') }}</td>
                            <td>
                                @if ($surat->status == 'ditolak')
                                <span class="badge bg-danger d-flex align-items-center gap-1 px-2 py-1">
                                    <i class="bi bi-x-circle"></i> Ditolak
                                </span>
                                @elseif($surat->status == 'diperbaiki')
                                <span class="badge bg-warning d-flex align-items-center gap-1 px-2 py-1">
                                    <i class="bi bi-hourglass-split"></i> Menunggu Review
                                </span>
                                @endif
                            </td>
                            <td>
                                @if ($surat->komentar_revisi)
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="popover"
                                    data-bs-placement="top" title="Komentar Revisi"
                                    data-bs-content="{{ $surat->komentar_revisi }}">
                                    <i class="bi bi-chat-quote"></i> Lihat Komentar
                                </button>
                                @else
                                <span class="text-muted fst-italic">Belum ada komentar</span>
                                @endif
                            </td>
                            <td>
                                @if ($surat->file_revisi)
                                <a href="{{ asset('storage/' . $surat->file_revisi) }}" class="btn btn-sm btn-outline-primary" download>
                                    <i class="bi bi-file-earmark-arrow-down"></i> Download
                                </a>
                                @else
                                <span class="text-muted fst-italic">Belum diunggah</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <!-- Admin Upload Ulang -->
                                @if (Auth::user()->role == 'admin' && $surat->status == 'ditolak')
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $surat->id }}">
                                    <i class="bi bi-upload"></i> Upload Revisi
                                </button>

                                <!-- Upload Modal -->
                                <div class="modal fade" id="uploadModal{{ $surat->id }}" tabindex="-1" aria-labelledby="uploadModalLabel{{ $surat->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="uploadModalLabel{{ $surat->id }}">Upload Revisi Surat</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('surat-masuk', $surat->id) }}" method="POST" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="perbaikan_file" class="form-label">File Revisi</label>
                                                        <input type="file" class="form-control" id="perbaikan_file" name="perbaikan_file" accept=".pdf,.doc,.docx" required>
                                                        <div class="form-text">Format yang didukung: PDF, DOC, DOCX (Maks. 5MB)</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="keterangan_revisi" class="form-label">Keterangan Revisi</label>
                                                        <textarea class="form-control" id="keterangan_revisi" name="keterangan_revisi" rows="3" placeholder="Jelaskan perubahan yang dilakukan..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Upload & Kirim Ulang</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Pak Syarif Lihat Ulang -->
                                @if (Auth::user()->name == 'Pak Syarif' && $surat->status == 'diperbaiki')
                                <a href="{{ route('revisi.lihat', $surat->id) }}" class="btn btn-sm btn-info d-flex align-items-center justify-content-center gap-1">
                                    <i class="bi bi-eye"></i> Review Ulang
                                </a>
                                @endif

                                <!-- Semua user bisa lihat detail -->
                                <a href="{{ route('surat-keluar.show', $surat->id) }}" class="btn btn-sm btn-outline-secondary mt-1">
                                    <i class="bi bi-info-circle"></i> Detail
                                </a>

                                <!-- Button Revisi Data -->
                                @if (Auth::user()->role == 'admin' && $surat->status == 'ditolak')
                                <button type="button" class="btn btn-sm btn-warning mt-1" data-bs-toggle="modal" data-bs-target="#revisiModal{{ $surat->id }}">
                                    <i class="bi bi-pencil-square"></i> Revisi Data
                                </button>

                                <!-- Revisi Data Modal -->
                                <div class="modal fade" id="revisiModal{{ $surat->id }}" tabindex="-1" aria-labelledby="revisiModalLabel{{ $surat->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="revisiModalLabel{{ $surat->id }}">Revisi Data Surat</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('surat-revisi.update', $surat->id) }}" method="POST">
                                                <div class="modal-body">
                                                    @csrf
                                                    @method('PATCH')

                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label for="jenis_surat" class="form-label fw-medium">Jenis Surat</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text bg-light">
                                                                    <i class="bi bi-envelope-paper"></i>
                                                                </span>

                                                                @php
                                                                    // dd($surat);
                                                                @endphp
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
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="perihal" class="form-label fw-medium">Perihal Surat</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text bg-light">
                                                                    <i class="bi bi-chat-square-text"></i>
                                                                </span>
                                                                <input type="text" name="perihal" id="perihal" class="form-control" value="{{ $surat->perihal }}" placeholder="Perihal Surat" required>
                                                            </div>
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
                                                                <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $surat->tanggal }}" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label for="isi_surat" class="form-label fw-medium">Isi Surat</label>
                                                            <textarea name="isi_surat" id="isi_surat" rows="5" class="form-control" required>{{ $surat->isi }}</textarea>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                                                <i class="bi bi-info-circle-fill me-2"></i>
                                                                <div>
                                                                    <strong>Komentar Revisi:</strong>
                                                                    {{ $surat->komentar_revisi ?: 'Tidak ada komentar revisi' }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach

                        @if(count($revisiList) == 0)
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">Tidak ada surat yang perlu direvisi saat ini</p>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Menampilkan {{ count($revisiList) }} surat yang perlu direvisi
                </div>
                @if(count($revisiList) > 0)
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
                @endif
            </div>
        </div>
    </div>

    <!-- Panduan Revisi Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="bi bi-info-circle me-2"></i>Panduan Revisi Surat
            </h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info mb-3" role="alert">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-lightbulb-fill fs-4 me-2"></i>
                    </div>
                    <div>
                        <h6 class="alert-heading fw-bold">Dua Cara Revisi</h6>
                        <p class="mb-0">Anda dapat melakukan revisi dengan dua cara:</p>
                        <ol class="mb-0 mt-1">
                            <li><strong>Revisi Data</strong> - Untuk memperbaiki informasi surat seperti perihal, tanggal, atau isi</li>
                            <li><strong>Upload Revisi</strong> - Untuk mengunggah dokumen revisi yang telah diperbaiki</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 bg-light rounded-circle p-3 me-3">
                            <i class="bi bi-1-circle text-primary fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold">Periksa Komentar</h6>
                            <p class="text-muted mb-0">Baca komentar revisi untuk memahami perubahan yang diperlukan</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 bg-light rounded-circle p-3 me-3">
                            <i class="bi bi-2-circle text-primary fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold">Upload Revisi</h6>
                            <p class="text-muted mb-0">Upload dokumen yang sudah diperbaiki sesuai komentar</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 bg-light rounded-circle p-3 me-3">
                            <i class="bi bi-3-circle text-primary fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold">Tunggu Persetujuan</h6>
                            <p class="text-muted mb-0">Setelah diupload, surat akan direview kembali untuk persetujuan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize popovers
    document.addEventListener('DOMContentLoaded', function() {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl, {
                html: true,
                sanitize: false
            })
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            table = document.getElementById('revisiTable');
            tr = table.getElementsByTagName('tr');

            for (i = 0; i < tr.length; i++) {
                // Skip header row
                if (i === 0) continue;

                let visible = false;
                // Check columns 0, 1, 2 (nomor surat, perihal, tanggal)
                for (let j = 0; j < 3; j++) {
                    td = tr[i].getElementsByTagName('td')[j];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            visible = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = visible ? '' : 'none';
            }
        });
    });
</script>
@endsection
