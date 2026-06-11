@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-black mb-6">Semua Kegiatan</h1>
    <p class="text-gray-500">Halaman ini akan menampilkan semua kegiatan dari database.</p>
    <a href="{{ route('home') }}" class="inline-block mt-4 text-[#2F7F79] font-bold hover:underline">← Kembali ke Home</a>
</div>
@endsection
