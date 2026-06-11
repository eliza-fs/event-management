@extends('layouts.app')
@section('title', 'Kegiatan Organisasi | VolunteerHub')

@push('styles')
<style>
    .event-card-shadow { box-shadow: 0 10px 30px -5px rgba(47,127,121,0.05); }
</style>
@endpush

@section('content')
<main class="flex-grow pt-8 pb-20 bg-[#f7f9fc]">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2 text-xs md:text-sm text-gray-500 font-medium">
                <a href="{{ route('organizations.index') }}" class="hover:text-[#2f7f79] flex items-center gap-1">
                    <span class="material-symbols-outlined text-base">corporate_fare</span>
                    <span>Organisasi</span>
                </a>
                <span class="text-gray-300">/</span>
                <span class="font-semibold text-gray-700">{{ $organization->org_name }}</span>
            </div>
            <a href="{{ route('organizations.index') }}"
               class="flex items-center gap-1.5 text-xs font-bold text-[#2f7f79] hover:text-[#256661] px-3 py-1.5 bg-teal-50/75 rounded-lg border border-teal-100 transition-colors">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                <span>Kembali ke Daftar</span>
            </a>
        </div>

        {{-- Banner Organisasi --}}
        <section class="bg-white rounded-2xl p-6 md:p-8 border border-gray-100 mb-10 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-start gap-4 md:gap-6">
                <div class="shrink-0">
                    @php($emoji = $organization->category->emoji ?? null)
                    <div
                        class="w-16 h-16 rounded-2xl border border-teal-100 shadow-sm flex items-center justify-center text-white"
                        style="background-color: {{ $organization->avatar_color }}">
                        @if($emoji)
                            <span class="text-3xl leading-none">{{ $emoji }}</span>
                        @else
                            <span class="text-lg font-bold">{{ $organization->avatar_initials }}</span>
                        @endif
                    </div>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h1 class="text-xl md:text-2xl font-extrabold text-gray-900 tracking-tight">
                            {{ $organization->org_name }}
                        </h1>
                        <span class="text-[10px] font-bold uppercase py-0.5 px-3 rounded-full bg-teal-50 text-teal-700 border border-teal-100">
                            {{ $organization->category->name ?? 'Kategori' }}
                        </span>
                    </div>
                    <p class="text-xs md:text-sm text-gray-500 mt-1.5 max-w-2xl leading-relaxed">
                        {{ $organization->description ?? 'Deskripsi organisasi.' }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-4 bg-teal-50/50 rounded-xl px-5 py-3 border border-teal-100/50">
                <div class="text-center">
                    <span class="block text-2xl font-extrabold text-[#2f7f79]">{{ isset($events) ? $events->count() : 0 }}</span>
                    <span class="text-[10px] text-gray-500 font-extrabold uppercase tracking-wider">Kegiatan Aktif</span>
                </div>
                <div class="w-px h-8 bg-teal-100"></div>
                <div>
                    <span class="block text-[10px] text-gray-400 font-bold uppercase">Status</span>
                    <span class="text-xs text-emerald-600 font-semibold flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block animate-pulse"></span> Verified
                    </span>
                </div>
            </div>
        </section>

        {{-- Header + Search --}}
        <div class="flex flex-col md:flex-row items-start justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl md:text-2xl font-extrabold text-[#263238] tracking-tight">Daftar Kegiatan Sosial Tersedia</h2>
                <p class="text-xs text-gray-400 mt-0.5">Pilih salah satu kegiatan yang bisa Anda ikuti.</p>
            </div>
            <div class="relative w-full md:w-80">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base">search</span>
                <input type="text" id="eventSearchInput" onkeyup="filterEvents()"
                       placeholder="Cari kegiatan..."
                       class="w-full pl-9 pr-4 py-2 border border-gray-200 bg-white rounded-xl text-xs outline-none focus:ring-1 focus:ring-[#2f7f79] focus:border-[#2f7f79] transition-all text-[#263238]"/>
            </div>
        </div>

        {{-- Empty state --}}
        <div id="no-events-view" class="hidden bg-white rounded-2xl py-12 px-6 border border-gray-100 text-center space-y-3 shadow-sm">
            <span class="material-symbols-outlined text-4xl text-gray-300">volunteer_activism</span>
            <p class="text-sm font-semibold text-gray-600">Tidak ada kegiatan yang cocok.</p>
        </div>

        {{-- Grid Kegiatan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="eventsContainer">
            @if(isset($events) && $events->count() > 0)
                @foreach($events as $event)
                <div onclick="window.location.href='{{ route('event.detail', $event->id) }}'"
                     class="event-card group cursor-pointer bg-white rounded-[24px] overflow-hidden border border-gray-100 hover:shadow-lg hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="h-52 w-full relative overflow-hidden bg-slate-50">
                            <x-event-image
                                :url="$event->image_url"
                                :has-image="$event->has_stored_image"
                                :alt="$event->title"
                                class="w-full h-full group-hover:scale-105 transition-transform duration-500"
                                img-class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                placeholder-class="w-full h-full"
                            />
                            <div class="absolute top-4 left-4 flex flex-col items-start gap-1.5 z-10">
                                <span class="bg-white text-[#29706a] font-extrabold text-[10px] uppercase tracking-wider px-3.5 py-1.5 rounded-lg shadow-sm border border-black/5">{{ $event->category->name ?? 'Kategori' }}</span>
                                <span class="bg-[#2f7f79] text-white font-extrabold text-[10px] uppercase tracking-wider px-3.5 py-1.5 rounded-lg shadow-sm">{{ $event->eventType->name ?? 'Onsite' }}</span>
                            </div>
                        </div>
                        <div class="p-5 md:p-6 pb-2 space-y-3">
                            <div class="flex items-center justify-between text-xs font-bold">
                                <div class="flex items-center gap-1.5 text-[#2f7f79] uppercase text-[10.5px] font-extrabold">
                                    <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1">verified</span>
                                    {{ strtoupper($organization->org_name) }}
                                </div>
                                <span class="{{ ($event->price ?? 0) == 0 ? 'text-[#2f7f79]' : 'text-orange-500' }} font-extrabold text-xs">
                                    {{ $event->isFree() ? 'Gratis' : 'Rp'.number_format($event->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <h3 class="event-title-text font-bold text-[16px] text-slate-900 group-hover:text-[#2f7f79] transition-colors leading-snug line-clamp-2">{{ $event->title }}</h3>
                            <p class="event-desc-text text-xs text-gray-500 line-clamp-2 leading-relaxed">{{ $event->description }}</p>
                        </div>
                    </div>
                    <div class="p-5 md:p-6 pt-0">
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100 mt-2">
                            <div class="space-y-1">
                                <div class="flex items-center gap-1.5 text-xs text-gray-500 font-medium">
                                    <span class="material-symbols-outlined text-base text-gray-400">group</span>
                                    Kuota: <strong class="text-slate-800">{{ ($event->approved_count ?? 0) }}/{{ $event->quota }}</strong>
                                </div>
                                <div class="flex items-center gap-1.5 text-xs text-gray-400 font-medium">
                                    <span class="material-symbols-outlined text-base text-gray-400">calendar_month</span>
                                    {{ $event->start_date?->format('d M Y') ?? '-' }}
                                </div>
                            </div>
                            <button class="bg-[#2f7f79] hover:bg-[#256661] text-white text-xs font-bold px-5 py-2.5 rounded-xl transition-all shadow-md active:scale-95">Details</button>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                {{-- Data dummy sementara --}}
                <div class="col-span-full text-center py-16">
                    <span class="material-symbols-outlined text-5xl text-gray-300">volunteer_activism</span>
                    <p class="text-gray-500 font-medium mt-4">Belum ada kegiatan aktif untuk organisasi ini.</p>
                    <a href="{{ route('home') }}" class="inline-block mt-4 text-sm font-bold text-[#2f7f79] hover:underline">Lihat semua kegiatan</a>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function filterEvents() {
        const query = document.getElementById('eventSearchInput').value.toLowerCase();
        const cards = document.getElementsByClassName('event-card');
        const noEvents = document.getElementById('no-events-view');
        let visible = 0;

        for (let i = 0; i < cards.length; i++) {
            const title = cards[i].querySelector('.event-title-text')?.innerText.toLowerCase() ?? '';
            const desc  = cards[i].querySelector('.event-desc-text')?.innerText.toLowerCase() ?? '';
            const show  = title.includes(query) || desc.includes(query);
            cards[i].style.display = show ? '' : 'none';
            if (show) visible++;
        }
        noEvents.classList.toggle('hidden', visible > 0 || cards.length === 0);
    }
</script>
@endpush
