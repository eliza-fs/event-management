@extends('layouts.app')

@section('title', 'VolunteerHub | Daftar Organisasi Relawan')

@push('styles')
<style>
    .editorial-shadow { box-shadow: 0 20px 40px -10px rgba(25, 28, 30, 0.08); }
</style>
@endpush

@section('content')

<main class="flex-grow pb-16">

    {{-- ===== HERO BANNER ===== --}}
    <section class="relative bg-teal-950 overflow-hidden mb-12">
        <div class="absolute inset-0 z-0 select-none">
            <img class="w-full h-full object-cover opacity-35 filter brightness-75 scale-105"
                 src="https://images.unsplash.com/photo-1593113598332-cd288d649433?q=80&w=1600&auto=format&fit=crop"
                 alt="Volunteer Background"/>
            <div class="absolute inset-0 bg-gradient-to-b from-[#2f7f79]/85 via-[#2f7f79]/80 to-[#f7f9fc]/90"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 pt-16 pb-20 text-center text-white">
            <h1 class="text-3xl md:text-5xl font-extrabold font-['Lexend'] leading-tight tracking-tight select-none">
                Temukan Kemitraan Terbaik Anda
            </h1>
            <p class="text-teal-50/95 text-sm md:text-base max-w-2xl mx-auto mt-3 leading-relaxed font-medium">
                Berkenalan dengan organisasi sosial terkemuka dan pilih kampanye yang selaras dengan panggilan kemanusiaan Anda.
            </p>

            {{-- Search Bar --}}
            <div class="max-w-2xl mx-auto mt-8">
                <div class="w-full bg-white rounded-2xl p-1.5 flex items-center shadow-2xl border border-white/15 group focus-within:ring-2 focus-within:ring-[#2f7f79]/50 transition-all">
                    <span class="material-symbols-outlined px-3.5 text-gray-400">search</span>
                    <input id="orgSearchInput" onkeyup="liveSearch()"
                           class="w-full border-none focus:ring-0 bg-transparent text-[#263238] text-base py-2 pl-1 pr-4 outline-none placeholder:text-gray-400 font-medium"
                           placeholder="Cari nama organisasi atau bidang sosial..."
                           type="text"/>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 inset-x-0 h-8 bg-gradient-to-t from-[#f7f9fc] to-transparent pointer-events-none"></div>
    </section>

    {{-- ===== GRID ORGANISASI ===== --}}
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6" id="orgGrid">

            @forelse($organizations as $org)
                    <div onclick="window.location.href='{{ route('organization.events', $org) }}'"
                         class="org-card group cursor-pointer bg-white rounded-2xl overflow-hidden editorial-shadow border border-gray-100 hover:border-teal-500/30 transition-all duration-300 transform hover:-translate-y-1.5 flex flex-col justify-between h-[280px]"
                         data-search-name="{{ strtolower($org->org_name) }}"
                         data-search-desc="{{ strtolower($org->description ?? '') }}"
                         data-search-category="{{ strtolower($org->category->name ?? '') }}">
                        <div>
                            <div class="h-28 relative bg-slate-100">
                                @if($org->has_stored_image)
                                    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                         src="{{ $org->image_url }}" alt="{{ $org->org_name }}"
                                         onerror="this.onerror=null;this.style.display='none';this.nextElementSibling.style.display='flex';"/>
                                    <div class="w-full h-full hidden items-center justify-center text-3xl font-black text-white"
                                         style="background-color: {{ $org->avatar_color }}">
                                        {{ $org->avatar_initials }}
                                    </div>
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-3xl font-black text-white"
                                         style="background-color: {{ $org->avatar_color }}">
                                        {{ $org->avatar_initials }}
                                    </div>
                                @endif
                                <div class="absolute -bottom-4 left-4">
                                    @php($emoji = $org->category->emoji ?? null)
                                    <div
                                        class="w-11 h-11 rounded-full border-2 border-white shadow-md flex items-center justify-center text-white"
                                        style="background-color: {{ $org->avatar_color }}">
                                        @if($emoji)
                                            <span class="text-base leading-none">{{ $emoji }}</span>
                                        @else
                                            <span class="text-xs font-bold">{{ $org->avatar_initials }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="pt-6 px-4 space-y-1.5">
                                <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full inline-block">{{ $org->category->name ?? '-' }}</span>
                                <h4 class="org-name text-sm font-extrabold text-[#263238] group-hover:text-[#2f7f79] transition-colors truncate">{{ $org->org_name }}</h4>
                                <p class="org-desc text-xs text-gray-500 line-clamp-2 leading-relaxed">{{ $org->description }}</p>
                            </div>
                        </div>
                        <div class="p-4 border-t border-gray-50 bg-slate-50/50 flex items-center justify-between text-xs text-[#2f7f79] font-bold">
                            <span>{{ $org->active_events_count }} Kegiatan Aktif</span>
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </div>
                    </div>
            @empty
                <p class="col-span-full text-center text-gray-500 py-12">Belum ada organisasi terdaftar.</p>
            @endforelse

        </div>

        @if($organizations->hasPages())
        <div class="mt-10 flex justify-center">
            {{ $organizations->links() }}
        </div>
        @endif
    </div>

</main>

@endsection

@push('scripts')
<script>
    function liveSearch() {
        let input = document.getElementById('orgSearchInput').value.toLowerCase();
        let cards = document.getElementsByClassName('org-card');
        let grid  = document.getElementById('orgGrid');

        if (input.length > 0) {
            const yOffset = -100;
            const yPos = grid.getBoundingClientRect().top + window.pageYOffset + yOffset;
            window.scrollTo({ top: yPos, behavior: 'smooth' });
        }

        for (let i = 0; i < cards.length; i++) {
            let name = cards[i].dataset.searchName || '';
            let desc = cards[i].dataset.searchDesc || '';
            let cat = cards[i].dataset.searchCategory || '';
            let match = !input || name.includes(input) || desc.includes(input) || cat.includes(input);
            cards[i].style.display = match ? '' : 'none';
        }
    }
</script>
@endpush
