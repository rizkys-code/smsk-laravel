@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Menu Revisi Surat</h2>
    </div>

    <!-- Daftar Surat untuk Revisi -->
    <div class="card">
        <div class="card-header">
            <strong>Surat yang Perlu Direvisi</strong>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nomor Surat</th>
                        <th>Status</th>
                        <th>Komentar Revisi</th>
                        <th>Dokumen Revisi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($revisiList as $surat)
                        <tr>
                            <td>{{ $surat->nomor_surat }}</td>
                            <td>
                                @if ($surat->status == 'ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @elseif($surat->status == 'diperbaiki')
                                    <span class="badge bg-warning">Menunggu Review</span>
                                @endif
                            </td>
                            <td>
                                @if ($surat->komentar_revisi)
                                    <div class="text-danger">{{ $surat->komentar_revisi }}</div>
                                @else
                                    <em>Belum ada komentar</em>
                                @endif
                            </td>
                            <td>
                                @if ($surat->file_revisi)
                                    <a href="{{ asset('storage/' . $surat->file_revisi) }}"
                                        class="btn btn-sm btn-outline-secondary" download>
                                        Download Revisi
                                    </a>
                                @else
                                    <span class="text-muted">Belum diunggah</span>
                                @endif
                            </td>
                            <td>
                                <!-- Admin Upload Ulang -->
                                @if (Auth::user()->role == 'admin' && $surat->status == 'ditolak')
                                    <form action="{{ route('surat-masuk', $surat->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" name="perbaikan_file" accept=".pdf,.doc,.docx" required>
                                        <button class="btn btn-sm btn-primary mt-1">Upload & Kirim Ulang</button>
                                    </form>
                                @endif

                                <!-- Pak Syarif Lihat Ulang -->
                                @if (Auth::user()->name == 'Pak Syarif' && $surat->status == 'diperbaiki')
                                    <a href="{{ route('revisi.lihat', $surat->id) }}" class="btn btn-sm btn-info">Review
                                        Ulang</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
