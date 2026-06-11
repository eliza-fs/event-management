@extends('layouts.app')

@section('title', 'VolunteerHub - Temukan Kontribusimu')

@section('content')

{{-- Semua konten home dibungkus Alpine.js x-data --}}
<div x-data="{
    searchQuery: '',
    selectedFilters: [],
    selectedType: 'All',
    selectedPrice: 'All',
    selectedEvent: null,

    events: @js($eventsData),

    filteredEvents() {
        return this.events.filter(event => {
            const q = this.searchQuery.toLowerCase().trim();
            const searchMatch =
                q === '' ||
                event.title.toLowerCase().includes(q) ||
                event.org.toLowerCase().includes(q) ||
                event.desc.toLowerCase().includes(q) ||
                event.subCat.toLowerCase().includes(q);

            const filterMatch =
                this.selectedFilters.length === 0 ||
                this.selectedFilters.includes(event.subCat);

            const typeMatch =
                this.selectedType === 'All' ||
                event.type === this.selectedType;

            const priceMatch =
                this.selectedPrice === 'All' ||
                (this.selectedPrice === 'Free' && event.price === 0) ||
                (this.selectedPrice === 'Paid' && event.price > 0);

            return searchMatch && filterMatch && typeMatch && priceMatch;
        });
    },

    formatPrice(price) {
        if (!price || price == 0) {
            return 'Gratis';
        }

        return 'Rp ' + Number(price).toLocaleString('id-ID');
    }
}">

    <!-- Hero Search -->
    <section class="relative h-112.5 md:h-125 flex items-center justify-center overflow-hidden">
        <img src="https://images.unsplash.com/photo-1593113598332-cd288d649433?w=1000&auto=format&fit=crop&q=60" class="absolute inset-0 w-full h-full object-cover" alt="Volunteer Partnership">
        <div class="absolute inset-0 bg-linear-to-b from-primary/80 via-primary/60 to-background-light"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-4xl md:text-6xl font-black text-white mb-4 drop-shadow-md">Temukan Kontribusimu</h2>
            <p class="text-white/90 text-lg md:text-xl mb-10 max-w-2xl mx-auto font-medium">Jelajahi ribuan peluang relawan yang sesuai dengan minat dan keahlianmu.</p>
            <div class="relative max-w-2xl mx-auto group">
                <div class="absolute inset-y-0 left-4 flex items-center text-zinc-400">
                    <span class="material-symbols-outlined">search</span>
                </div>
                <input type="text" x-model="searchQuery" placeholder="Cari aktivitas, skill, atau isu sosial..." class="w-full pl-12 pr-4 py-5 rounded-2xl border-none bg-white shadow-2xl focus:ring-4 focus:ring-primary/30 transition-all text-lg text-slate-900">
            </div>
        </div>
    </section>

    <main class="max-w-350 mx-auto px-6 py-10 flex flex-col lg:flex-row gap-10">

        <!-- Sidebar Filter -->
        <aside class="hidden lg:block w-64 shrink-0 space-y-8">
            <div>
                <div class="flex items-center gap-2 mb-6 text-primary">
                    <span class="material-symbols-outlined">tune</span>
                    <h3 class="font-bold text-lg">Filters</h3>
                </div>
                <div class="space-y-6">
                    @foreach($filterOptions ?? [] as $group => $subs)
                    <div class="space-y-2">
                        <h4 class="text-xs font-black uppercase text-zinc-400 mb-2 tracking-widest">{{ $group }}</h4>
                        @foreach($subs as $sub)
                        <label class="flex items-center gap-3 p-2 rounded-xl hover:bg-white cursor-pointer transition">
                            <input type="checkbox" value="{{ $sub }}" x-model="selectedFilters" class="rounded border-zinc-300 text-primary">
                            <span class="text-sm font-medium text-slate-700">{{ $sub }}</span>
                        </label>
                        @endforeach
                    </div>
                    @endforeach
                    <button type="button" @click="selectedFilters = []; selectedType = 'All'; selectedPrice = 'All';" class="text-xs font-bold text-red-500 hover:underline">Reset Semua Filter</button>
                </div>
            </div>
        </aside>

        <!-- Content Area -->
        <div class="flex-1">
            <div id="events-section" class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 scroll-mt-24">
                <div>
                    <h2 class="text-2xl font-black">Aktivitas Rekomendasi</h2>
                    <p class="text-sm text-zinc-500">Menampilkan <span x-text="filteredEvents().length"></span> hasil untukmu</p>
                </div>
                <div class="flex gap-3">
                    <select x-model="selectedType" class="bg-white border border-zinc-200 rounded-lg text-sm font-bold shadow-sm px-3 py-2">
                        <option value="All">Semua Tipe</option>
                        <option value="Online">Online</option>
                        <option value="Onsite">Onsite</option>
                    </select>
                    <select x-model="selectedPrice" class="bg-white border border-zinc-200 rounded-lg text-sm font-bold shadow-sm px-3 py-2">
                        <option value="All">Semua Biaya</option>
                        <option value="Free">Gratis</option>
                        <option value="Paid">Berbayar</option>
                    </select>
                </div>
            </div>

            <!-- Grid Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <template x-for="event in filteredEvents()" :key="event.id">
                    <div @click="selectedEvent = event" class="group bg-white rounded-2xl overflow-hidden border border-zinc-100 hover:shadow-2xl hover:shadow-primary/10 transition-all duration-300 flex flex-col cursor-pointer transform hover:-translate-y-1">
                        <div class="relative h-48 overflow-hidden">
                            <template x-if="event.hasImg">
                                <img :src="event.img" :alt="event.title" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                            </template>
                            <template x-if="!event.hasImg">
                                <div class="w-full h-full bg-gradient-to-br from-primary/25 to-primary/55 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white/80 text-5xl" style="font-variation-settings: 'FILL' 1">event</span>
                                </div>
                            </template>
                            <div class="absolute top-3 left-3 flex flex-col gap-2">
                                <span class="px-3 py-1 bg-white/90 backdrop-blur text-[10px] font-black uppercase rounded-lg text-primary shadow-sm" x-text="event.subCat"></span>
                                <span class="px-3 py-1 bg-primary text-white text-[10px] font-black uppercase rounded-lg shadow-sm" x-text="event.type"></span>
                            </div>
                        </div>
                        <div class="p-5 flex flex-col flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2 text-primary">
                                    <span class="material-symbols-outlined text-xs">verified</span>
                                    <span class="text-[10px] font-bold uppercase tracking-wider" x-text="event.org"></span>
                                </div>
                                <span class="text-[11px] font-black" :class="event.price === 0 ? 'text-green-600' : 'text-orange-600'" x-text="formatPrice(event.price)"></span>
                            </div>
                            <h3 class="font-bold text-lg mb-2 leading-tight group-hover:text-primary transition-colors" x-text="event.title"></h3>
                            <p class="text-sm text-zinc-500 line-clamp-2 mb-6" x-text="event.desc"></p>
                            <div class="mt-auto pt-4 border-t border-zinc-50 flex items-center justify-between">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-1 text-[10px] text-zinc-400">
                                        <span class="material-symbols-outlined text-xs">group</span>
                                        <span>Kuota: <span class="font-bold text-zinc-600" x-text="event.quota"></span></span>
                                    </div>
                                    <div class="flex items-center gap-1 text-[10px] text-zinc-400">
                                        <span class="material-symbols-outlined text-xs">calendar_today</span>
                                        <span x-text="event.date"></span>
                                    </div>
                                </div>
                                <button type="button" @click.stop="selectedEvent = event" class="px-4 py-2 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary/90 transition-all shadow-md">Detail</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </main>

    <!-- Modal Detail -->
    <div x-show="selectedEvent" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="selectedEvent = null" class="absolute inset-0 bg-zinc-950/60 backdrop-blur-sm"></div>
        <div class="relative bg-white w-full max-w-2xl rounded-3xl overflow-hidden shadow-2xl overflow-y-auto max-h-[90vh]">
            <template x-if="selectedEvent?.hasImg">
                <img :src="selectedEvent.img" :alt="selectedEvent.title" class="w-full h-64 object-cover">
            </template>
            <template x-if="selectedEvent && !selectedEvent.hasImg">
                <div class="w-full h-64 bg-gradient-to-br from-primary/25 to-primary/55 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white/80 text-6xl" style="font-variation-settings: 'FILL' 1">event</span>
                </div>
            </template>
            <button type="button" @click="selectedEvent = null" class="absolute top-4 right-4 z-10 bg-black/50 text-white p-2 rounded-full hover:bg-black transition-colors flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-2xl leading-none">close</span>
            </button>
            <div class="p-8">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2 text-primary">
                        <span class="material-symbols-outlined">verified</span>
                        <span class="font-bold uppercase text-xs" x-text="selectedEvent?.org"></span>
                    </div>
                    <span class="px-4 py-1 bg-zinc-100 rounded-full text-sm font-black text-primary" x-text="formatPrice(selectedEvent?.price)"></span>
                </div>
                <h2 class="text-3xl font-black mb-4 text-slate-900" x-text="selectedEvent?.title"></h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="flex flex-col p-3 bg-zinc-50 rounded-2xl border border-zinc-100">
                        <span class="text-[10px] text-zinc-400 uppercase font-bold">Kuota</span>
                        <span class="text-sm font-bold text-slate-700" x-text="selectedEvent?.quota"></span>
                    </div>
                    <div class="flex flex-col p-3 bg-zinc-50 rounded-2xl border border-zinc-100">
                        <span class="text-[10px] text-zinc-400 uppercase font-bold">Tipe</span>
                        <span class="text-sm font-bold text-slate-700" x-text="selectedEvent?.type"></span>
                    </div>
                    <div class="flex flex-col p-3 bg-zinc-50 rounded-2xl border border-zinc-100">
                        <span class="text-[10px] text-zinc-400 uppercase font-bold">Tanggal</span>
                        <span class="text-sm font-bold text-slate-700" x-text="selectedEvent?.date"></span>
                    </div>
                    <div class="flex flex-col p-3 bg-zinc-50 rounded-2xl border border-zinc-100">
                        <span class="text-[10px] text-zinc-400 uppercase font-bold">Lokasi</span>
                        <span class="text-sm font-bold truncate text-slate-700" x-text="selectedEvent?.location"></span>
                    </div>
                </div>
                <p class="text-zinc-600 leading-relaxed mb-8" x-text="selectedEvent?.desc"></p>
                <div class="flex gap-4">
                    <template x-if="selectedEvent?.registrationOpen">
                        <a :href="'{{ url('/events') }}/' + selectedEvent?.id + '/register'" class="flex-1 py-4 bg-primary text-white font-black rounded-2xl shadow-xl hover:scale-[1.02] transition flex items-center justify-center">Daftar Sekarang</a>
                    </template>
                    <template x-if="selectedEvent && !selectedEvent.registrationOpen">
                        <div class="flex-1 py-4 bg-gray-200 text-gray-500 font-black rounded-2xl text-center cursor-not-allowed">
                            Pendaftaran Ditutup
                            <p class="text-xs font-semibold mt-1 text-red-600">Pendaftaran ditutup 3 hari sebelum acara dimulai.</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

</div>{{-- end x-data --}}

@endsection
