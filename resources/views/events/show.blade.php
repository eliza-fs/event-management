@extends('layouts.app')
@section('title', ($event->title ?? 'Detail Event') . ' | VolunteerHub')

@section('content')
@php $canRegister = $event->isOpen(); @endphp
<main class="mx-auto max-w-[1200px] w-full px-4 py-6 md:px-6 lg:px-10">

    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 rounded-xl p-4 text-red-700 text-sm font-bold">{{ session('error') }}</div>
    @endif

    {{-- Breadcrumb --}}
    <nav class="flex flex-wrap gap-2 mb-4 text-xs md:text-sm">
        <a class="text-[#6b806c] font-medium hover:underline" href="{{ route('home') }}">Beranda</a>
        <span class="text-[#6b806c]">/</span>
        <a class="text-[#6b806c] font-medium hover:underline" href="{{ route('organizations.index') }}">Organisasi</a>
        <span class="text-[#6b806c]">/</span>
        <span class="text-primary font-medium truncate max-w-[200px] sm:max-w-none">{{ $event->title ?? 'Detail Kegiatan' }}</span>
    </nav>

    {{-- Banner Image --}}
    <div class="mb-6 md:mb-8">
        <x-event-image
            :url="$event->image_url"
            :has-image="$event->has_stored_image"
            :alt="$event->title"
            class="w-full rounded-2xl min-h-[240px] sm:min-h-[350px] md:min-h-[420px] shadow-sm overflow-hidden"
            img-class="w-full min-h-[240px] sm:min-h-[350px] md:min-h-[420px] object-cover"
            placeholder-class="w-full min-h-[240px] sm:min-h-[350px] md:min-h-[420px]"
        />
    </div>

    <div class="flex flex-col lg:flex-row gap-6 md:gap-8">

        {{-- Konten Kiri --}}
        <div class="flex-1 min-w-0 order-2 lg:order-1">

            {{-- Judul & Badge --}}
            <div class="flex flex-col gap-2 mb-6">
                <h1 class="text-[#131613] text-2xl md:text-4xl font-black leading-tight tracking-tight">
                    {{ $event->title }}
                </h1>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-[10px] md:text-xs font-bold uppercase tracking-wider">
                        {{ $event->category->name ?? 'Volunteer' }}
                    </span>
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[10px] md:text-xs font-bold uppercase tracking-wider">
                        {{ $event->eventType->name ?? 'Onsite' }}
                    </span>
                </div>
            </div>

            {{-- Info Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white p-4 rounded-xl border border-gray-100 flex items-start gap-4 shadow-sm">
                    <div class="bg-primary/10 p-2 rounded-lg text-primary shrink-0">
                        <span class="material-symbols-outlined block">calendar_today</span>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 font-bold uppercase">Tanggal & Waktu</p>
                        <p class="text-xs md:text-sm font-bold mt-0.5">
                            {{ $event->start_date?->format('d M Y') ?? '-' }}
                            @if($event->end_date) – {{ $event->end_date->format('d M Y') }} @endif
                        </p>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-100 flex items-start gap-4 shadow-sm">
                    <div class="bg-primary/10 p-2 rounded-lg text-primary shrink-0">
                        <span class="material-symbols-outlined block">location_on</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] text-gray-500 font-bold uppercase">Lokasi</p>
                        <p class="text-xs md:text-sm font-bold mt-0.5 break-words whitespace-normal">{{ $event->location ?? 'Jakarta, Indonesia' }}</p>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-100 flex items-start gap-4 shadow-sm">
                    <div class="bg-primary/10 p-2 rounded-lg text-primary shrink-0">
                        <span class="material-symbols-outlined block">group</span>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 font-bold uppercase">Kuota</p>
                        <p class="text-xs md:text-sm font-bold mt-0.5">{{ ($event->approved_count ?? 0) }}/{{ $event->quota }} peserta</p>
                    </div>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm mb-6">
                <h2 class="text-lg font-black text-[#131613] mb-4">Tentang Kegiatan</h2>
                <p class="text-sm text-gray-600 leading-relaxed">
                    {{ $event->description ?? 'Deskripsi kegiatan akan ditampilkan di sini.' }}
                </p>
            </div>

            {{-- Batas Pendaftaran --}}
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-center gap-3 mb-6">
                <span class="material-symbols-outlined text-amber-600">schedule</span>
                <div>
                    <p class="text-xs font-bold text-amber-800">Batas Pendaftaran</p>
                    <p class="text-sm font-bold text-amber-700">
                        {{ $event->registrationDeadline()?->format('d M Y') ?? '-' }}
                    </p>
                    <p class="text-xs text-amber-600 mt-1">Pendaftaran ditutup 3 hari sebelum acara dimulai.</p>
                </div>
            </div>
        </div>

        {{-- Sidebar Kanan --}}
        <div class="w-full lg:w-80 order-1 lg:order-2 shrink-0">
            <div class="sticky top-24 bg-white rounded-2xl border border-gray-100 shadow-xl p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <p class="text-2xl font-black text-primary">
                        {{ $event->isFree() ? 'Gratis' : 'Rp'.number_format($event->price, 0, ',', '.') }}
                    </p>
                    <span class="text-xs font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded-lg">per peserta</span>
                </div>

                <div class="space-y-2 text-xs text-gray-600">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="font-medium">Kategori</span>
                        <span class="font-bold text-primary">{{ $event->category->name ?? 'Volunteer' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="font-medium">Tipe</span>
                        <span class="font-bold">{{ $event->eventType->name ?? 'Onsite' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="font-medium">Sisa Kuota</span>
                        <span class="font-bold text-green-600">{{ max(0, $event->quota - ($event->approved_count ?? 0)) }} slot</span>
                    </div>
                </div>

                @if($canRegister)
                    <a href="{{ auth()->check() && auth()->user()->role === 'user' ? route('event.register.form', $event->id) : route('login') }}"
                       class="w-full block text-center py-4 bg-primary hover:bg-[#256661] text-white font-black rounded-2xl shadow-xl shadow-primary/30 hover:scale-[1.02] transition text-sm">
                        Daftar Sekarang
                    </a>
                @else
                    <button type="button" disabled
                            class="w-full block text-center py-4 bg-gray-200 text-gray-500 font-black rounded-2xl text-sm cursor-not-allowed">
                        Pendaftaran Ditutup
                    </button>
                    <p class="text-xs text-center text-red-600 font-semibold">{{ $event->registrationClosedReason() }}</p>
                @endif

                <button onclick="handleShare()"
                        class="w-full flex items-center justify-center gap-2 rounded-xl h-11 bg-gray-100 text-[#131613] text-sm font-bold hover:bg-gray-200 transition-colors">
                    <span class="material-symbols-outlined text-lg">share</span>
                    Bagikan Kegiatan
                </button>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function handleShare() {
        if (navigator.share) {
            navigator.share({
                title: document.title,
                url: window.location.href
            }).catch(console.error);
        } else {
            navigator.clipboard.writeText(window.location.href)
                .then(() => alert('Link berhasil disalin!'))
                .catch(() => alert('Gagal menyalin link.'));
        }
    }
</script>
@endpush
