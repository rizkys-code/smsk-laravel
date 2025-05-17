@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-1">Surat Keluar</h2>
                <p class="text-muted">Kelola dan pantau semua surat keluar</p>
            </div>
            <a href="#form-tambah" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-plus-circle"></i> Tambah Surat Keluar
            </a>
        </div>

        <!-- Status Alert -->
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>{{ session('status') }}</strong>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Daftar Surat Keluar -->
        <div class="card shadow-sm border-0 mb-5 overflow-hidden">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-envelope-paper me-2"></i>Daftar Surat Keluar
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
                    <table class="table table-hover align-middle mb-0" id="suratTable">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Jenis Surat</th>
                                <th class="py-3">No. Surat</th>
                                <th class="py-3">Tanggal</th>
                                <th class="py-3">Status</th>
                                <th class="py-3">Isi</th>
                                <th class="py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataSurat as $surat)
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

                                    $jenis_surat = $kodeSurat[$surat->jenis];

                                    $statusClass = match ($surat->status) {
                                        'menunggu' => 'warning',
                                        'ditolak' => 'danger',
                                        'disetujui' => 'success',
                                        default => 'secondary',
                                    };

                                    $statusIcon = match ($surat->status) {
                                        'menunggu' => 'bi-hourglass-split',
                                        'ditolak' => 'bi-x-circle',
                                        'disetujui' => 'bi-check-circle',
                                        default => 'bi-question-circle',
                                    };
                                @endphp
                                <tr>
                                    <td class="px-4">
                                        <span class="d-inline-block text-truncate" style="max-width: 200px;">
                                            {{ $jenis_surat }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $surat->nomor_surat }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($surat->tanggal)->format('d M Y') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="badge bg-{{ $statusClass }} d-flex align-items-center gap-1 px-2 py-1">
                                                <i class="bi {{ $statusIcon }}"></i>
                                                {{ ucfirst($surat->status) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 200px;">
                                            {{ $surat->isi }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-start gap-1">
                                            <a href="{{ route('surat-keluar.show', $surat->id) }}"
                                                class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <form action="{{ route('surat-keluar.destroy', $surat->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Yakin ingin menghapus?')"
                                                    data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>

                                            @if ($surat->status === 'ditolak')
                                                <a href="{{ route('surat-keluar.review', $surat->id) }}"
                                                    class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip"
                                                    title="Revisi">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @elseif ($surat->status === 'menunggu')
                                                <a href="{{ route('surat-keluar.review', $surat->id) }}"
                                                    class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip"
                                                    title="Review">
                                                    <i class="bi bi-clipboard-check"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-between align-items-center flex-column flex-md-row gap-2">
                    <div class="text-muted small">
                        Menampilkan {{ $dataSurat->firstItem() }} - {{ $dataSurat->lastItem() }} dari total
                        {{ $dataSurat->total() }} surat
                    </div>
                    {{ $dataSurat->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

        <!-- Form Tambah Surat Keluar -->
        <div class="card shadow-sm border-0 mb-4" id="form-tambah">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Surat Keluar
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('surat-keluar.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label for="jenis_surat" class="form-label fw-medium">Jenis Surat</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-envelope-paper"></i>
                            </span>
                            <select id="jenis_surat" name="jenis_surat" class="form-select" required>
                                <option value="">-- Pilih Jenis Surat --</option>
                                <option value="SA">Sertif Asisten / PKL</option>
                                <option value="SS">Sertif Webinar / Workshop / Media Partner</option>
                                <option value="H">Surat Perbaikan</option>
                                <option value="P">Formulir Pendaftaran Calas</option>
                                <option value="S">SK Asisten / Keterangan</option>
                                <option value="U">Surat Undangan</option>
                                <option value="K">Surat Keputusan</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="perihal" class="form-label fw-medium">Perihal Surat</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-chat-square-text"></i>
                            </span>
                            <input type="text" name="perihal" id="perihal" class="form-control"
                                placeholder="Perihal Surat" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-medium">Nomor Surat</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-hash"></i>
                            </span>
                            <input type="text" name="nomor_surat" class="form-control bg-light" value=""
                                placeholder="Otomatis" readonly>
                        </div>
                        <div class="form-text">Nomor surat akan dibuat otomatis</div>
                    </div>

                    <div class="col-md-4">
                        <label for="lampiran" class="form-label fw-medium">Lampiran (jumlah halaman)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-paperclip"></i>
                            </span>
                            <input type="number" name="lampiran" id="lampiran" class="form-control" min="0"
                                required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="tanggal" class="form-label fw-medium">Tanggal Surat</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-calendar-date"></i>
                            </span>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="isi_surat" class="form-label fw-medium">Isi Surat</label>
                        <textarea name="isi_surat" id="isi_surat" rows="5" class="form-control"
                            placeholder="Isi surat akan menyesuaikan template..." required></textarea>
                        <div class="form-text">Isi surat akan menyesuaikan template yang dipilih</div>
                    </div>

                    <!-- Lampiran Dinamis -->
                    <div class="col-12 mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="form-label fw-medium mb-0">
                                <i class="bi bi-paperclip me-1"></i>Lampiran Tambahan (Opsional)
                            </label>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="tambahLampiran()">
                                <i class="bi bi-plus-circle me-1"></i>Tambah Baris Lampiran
                            </button>
                        </div>
                        <div id="lampiran-container">
                            <div class="row mb-2 lampiran-group">
                                <div class="col-md-5">
                                    <input type="text" name="lampiran[0][label]" class="form-control"
                                        placeholder="Label (misal: Nama)">
                                </div>
                                <div class="col-md-7">
                                    <input type="text" name="lampiran[0][isi]" class="form-control"
                                        placeholder="Isi (misal: John Doe)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <div class="d-flex gap-2">
                            <button type="submit" name="aksi" value="minta_persetujuan" class="btn btn-primary">
                                <i class="bi bi-send me-1"></i>Minta Persetujuan
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // fungsi buat search
            document.getElementById('searchInput').addEventListener('keyup', function() {
                var input, filter, table, tr, td, i, txtValue;
                input = document.getElementById('searchInput');
                filter = input.value.toUpperCase();
                table = document.getElementById('suratTable');
                tr = table.getElementsByTagName('tr');

                for (i = 0; i < tr.length; i++) {
                    // ini buat ngeskip header row
                    if (i === 0) continue;

                    let visible = false;
                    for (let j = 0; j < 5; j++) {
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

        let indexLampiran = 1;

        function tambahLampiran() {
            const container = document.getElementById('lampiran-container');
            const row = document.createElement('div');
            row.className = 'row mb-2 lampiran-group';
            row.innerHTML = `
            <div class="col-md-5">
                <div class="input-group">
                    <input type="text" name="lampiran[${indexLampiran}][label]" class="form-control" placeholder="Label">
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" name="lampiran[${indexLampiran}][isi]" class="form-control" placeholder="Isi">
                </div>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="hapusLampiran(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
            container.appendChild(row);
            indexLampiran++;
        }

        function hapusLampiran(button) {
            const row = button.closest('.lampiran-group');
            row.remove();
        }
    </script>
@endsection
