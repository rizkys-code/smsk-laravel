@extends('layouts.app')

@section('content')
<h2>Detail Surat Keluar</h2>
<ul>
    <li>Nomor: {{ $surat->nomor_surat }}</li>
    <li>Tanggal: {{ $surat->tanggal }}</li>
    <li>Perihal: {{ $surat->perihal }}</li>
    <li>Status: {{ $surat->status }}</li>
    <li>Isi: {{ $surat->isi }}</li>
</ul>
<a href="{{ route('surat-keluar') }}" class="btn btn-secondary">Kembali</a>
@endsection
