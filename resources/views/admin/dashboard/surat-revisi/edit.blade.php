@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 bg-white p-6 shadow rounded">
    <h2 class="text-2xl font-bold mb-4">Formulir Revisi Surat</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('surat-revisi.update', $revisi->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Komentar Revisi --}}
        <div class="mb-4">
            <label for="komentar_revisi" class="block font-semibold mb-1">Komentar Revisi</label>
            <textarea name="komentar_revisi" id="komentar_revisi" rows="4"
                class="w-full border rounded p-2 @error('komentar_revisi') border-red-500 @enderror"
                required>{{ old('komentar_revisi', $revisi->komentar_revisi) }}</textarea>
            @error('komentar_revisi')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- File Revisi (Opsional) --}}
        <div class="mb-4">
            <label for="file_revisi" class="block font-semibold mb-1">Unggah File Revisi (PDF / DOCX)</label>
            <input type="file" name="file_revisi" id="file_revisi"
                class="w-full border rounded p-2 @error('file_revisi') border-red-500 @enderror"
                accept=".pdf,.docx">
            @error('file_revisi')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            @if ($revisi->file_revisi)
                <p class="mt-2 text-sm">
                    File revisi saat ini:
                    <a href="{{ asset('storage/' . $revisi->file_revisi) }}" target="_blank" class="text-blue-600 underline">Lihat</a>
                </p>
            @endif
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end">
            <a href="{{ route('surat-revisi') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 mr-2">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Simpan Revisi
            </button>
        </div>
    </form>
</div>
@endsection
