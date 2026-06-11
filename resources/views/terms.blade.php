@extends('layouts.app')
@section('title', 'Support & Legal | VolunteerHub')

@section('content')
<div x-data="{ activeFaq: null, activeTab: 'faq' }">

    {{-- Page Header --}}
    <div class="pt-12 pb-8 px-6 max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="max-w-2xl">
                <nav class="flex items-center gap-2 text-xs text-primary font-bold mb-4">
                    <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
                    <span class="material-symbols-outlined text-xs">chevron_right</span>
                    <span>Support & Legal</span>
                </nav>
                <h1 class="text-3xl md:text-6xl font-black text-slate-900 mb-4 tracking-tight">Support & Legal</h1>
                <p class="text-sm md:text-lg text-slate-600 leading-relaxed font-medium">
                    Kami di sini untuk membantu Anda menciptakan dampak positif. Temukan jawaban atas pertanyaan umum, pelajari panduan komunitas, atau hubungi tim kami.
                </p>
            </div>
            <div class="hidden lg:block">
                <div class="px-6 py-4 rounded-3xl bg-white shadow-xl border border-zinc-100 flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings:'FILL' 1">verified</span>
                    <div>
                        <p class="font-black text-primary leading-none uppercase text-xs tracking-wider">Platform Terpercaya</p>
                        <p class="text-zinc-500 text-[10px] font-bold mt-1">Resmi & Terverifikasi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-6 pb-24 flex flex-col lg:flex-row gap-8 lg:gap-12">

        {{-- Sidebar --}}
        <aside class="w-full lg:w-1/4">
            <div class="sticky top-28 space-y-4">
                <div class="bg-white p-3 rounded-3xl border border-zinc-100 shadow-sm">
                    <nav class="flex flex-row lg:flex-col overflow-x-auto gap-1">
                        <a @click="activeTab = 'faq'"
                           :class="activeTab === 'faq' ? 'text-primary font-bold bg-primary/5' : 'text-slate-500 hover:text-primary hover:bg-primary/5'"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all whitespace-nowrap text-sm flex-1 lg:flex-none cursor-pointer" href="#faq">
                            <span class="material-symbols-outlined">quiz</span> Pertanyaan (FAQ)
                        </a>
                        <a @click="activeTab = 'terms'"
                           :class="activeTab === 'terms' ? 'text-primary font-bold bg-primary/5' : 'text-slate-500 hover:text-primary hover:bg-primary/5'"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all whitespace-nowrap text-sm flex-1 lg:flex-none cursor-pointer" href="#terms">
                            <span class="material-symbols-outlined">gavel</span> Ketentuan Layanan
                        </a>
                        <a @click="activeTab = 'contact'"
                           :class="activeTab === 'contact' ? 'text-primary font-bold bg-primary/5' : 'text-slate-500 hover:text-primary hover:bg-primary/5'"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all whitespace-nowrap text-sm flex-1 lg:flex-none cursor-pointer" href="#contact">
                            <span class="material-symbols-outlined">mail</span> Hubungi Kami
                        </a>
                    </nav>
                </div>
            </div>
        </aside>

        {{-- Konten --}}
        <div class="w-full lg:w-3/4 space-y-16 md:space-y-20">

            {{-- FAQ --}}
            <section class="scroll-mt-28" id="faq">
                <div class="flex items-center gap-4 mb-6 md:border-b md:border-zinc-200 md:pb-4">
                    <h2 class="text-2xl md:text-3xl font-black tracking-tight">Pertanyaan Populer</h2>
                </div>
                <div class="space-y-4">
                    @php
                        $faqs = [
                            [1, 'Bagaimana cara mendaftar di VolunteerHub?', 'Caranya sangat mudah. Klik tombol "Bergabung" di beranda kami, verifikasi email Anda, dan lengkapi profil relawan Anda. Anda akan siap mencari kegiatan dalam waktu kurang dari 5 menit.'],
                            [2, 'Apakah platform ini gratis untuk relawan?', 'Ya, VolunteerHub sepenuhnya gratis untuk digunakan oleh semua individu yang ingin menjadi relawan. Kami percaya bahwa kebaikan tidak boleh dibatasi oleh biaya.'],
                            [3, 'Bolehkah saya menjadi relawan di banyak organisasi?', 'Tentu saja! Anda dapat mendaftar ke berbagai program selama jadwal Anda memungkinkan. Pastikan Anda dapat memenuhi komitmen di setiap kegiatan yang Anda pilih.'],
                        ];
                    @endphp
                    @foreach($faqs as [$id, $q, $a])
                    <div class="bg-white rounded-2xl md:rounded-3xl border border-zinc-100 shadow-sm hover:border-primary/30 transition-all">
                        <button @click="activeFaq === {{ $id }} ? activeFaq = null : activeFaq = {{ $id }}"
                                class="w-full px-5 md:px-8 py-5 md:py-6 flex items-center justify-between text-left gap-4">
                            <span class="text-base md:text-lg font-bold text-slate-800">{{ $q }}</span>
                            <span class="material-symbols-outlined text-primary shrink-0 transition-transform duration-300"
                                  :class="activeFaq === {{ $id }} ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div x-show="activeFaq === {{ $id }}" x-collapse x-cloak>
                            <div class="px-5 md:px-8 pb-5 md:pb-6 text-xs md:text-sm text-slate-500 leading-relaxed font-medium">
                                {{ $a }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

            {{-- Ketentuan --}}
            <section class="scroll-mt-28" id="terms">
                <div class="flex items-center gap-4 mb-6 md:border-b md:border-zinc-200 md:pb-4">
                    <h2 class="text-2xl md:text-3xl font-black tracking-tight">Ketentuan Layanan</h2>
                </div>
                <div class="bg-white p-6 md:p-12 rounded-3xl border border-zinc-100 shadow-xl space-y-8 md:space-y-12">
                    @php
                        $terms = [
                            ['Ketentuan Umum', 'Dengan mengakses VolunteerHub, Anda setuju untuk mematuhi standar komunitas dan kebijakan perilaku digital kami. Platform kami adalah ruang kolaboratif yang dirancang untuk memfasilitasi dampak sosial positif melalui transparansi dan rasa saling menghormati.'],
                            ['Kebijakan Privasi', 'Privasi Anda adalah prioritas kami. Kami menggunakan enkripsi standar industri untuk melindungi data pribadi Anda. VolunteerHub tidak pernah menjual informasi Anda kepada pengiklan pihak ketiga.'],
                        ];
                    @endphp
                    @foreach($terms as [$title, $body])
                    <div class="group" @if($title === 'Kebijakan Privasi') id="privacy" @endif>
                        <h3 class="text-lg md:text-xl font-black text-primary mb-3 md:mb-4 flex items-center gap-3">
                            <span class="w-1.5 md:w-2 h-5 md:h-6 bg-primary rounded-full"></span>{{ $title }}
                        </h3>
                        <p class="text-xs md:text-sm text-slate-600 leading-relaxed font-medium">{{ $body }}</p>
                    </div>
                    @endforeach
                    <div class="group">
                        <h3 class="text-lg md:text-xl font-black text-primary mb-4 flex items-center gap-3">
                            <span class="w-1.5 md:w-2 h-5 md:h-6 bg-primary rounded-full"></span>Tanggung Jawab Relawan
                        </h3>
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 text-xs md:text-sm text-slate-600 font-bold">
                            @foreach(['Memberikan informasi akurat saat pendaftaran.','Menghormati komitmen yang dibuat kepada organisasi.','Menjaga sikap sopan dan profesional dalam interaksi.','Menjaga kerahasiaan data internal organisasi.'] as $item)
                            <li class="flex gap-3 p-4 bg-zinc-50 rounded-2xl border border-zinc-100">
                                <span class="material-symbols-outlined text-primary shrink-0">check_circle</span>
                                <span>{{ $item }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </section>

            {{-- Kontak --}}
            <section class="scroll-mt-28 pb-12" id="contact">
                <div class="flex items-center gap-4 mb-6 md:border-b md:border-zinc-200 md:pb-4 max-w-2xl mx-auto w-full">
                    <h2 class="text-2xl md:text-3xl font-black tracking-tight">Hubungi Kami</h2>
                </div>
                <div class="max-w-2xl mx-auto w-full space-y-6">
                    <div class="bg-white p-6 md:p-8 rounded-3xl border border-zinc-100 shadow-sm space-y-6">
                        <p class="text-sm md:text-lg text-slate-600 font-medium">Punya pertanyaan spesifik? Tim kami akan membalas dalam 24 jam.</p>
                        <div class="space-y-4">
                            <div class="flex items-center gap-4 p-4 md:p-5 rounded-2xl bg-zinc-50 border border-zinc-100">
                                <div class="h-12 w-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary shrink-0">
                                    <span class="material-symbols-outlined">location_on</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Kantor Pusat</p>
                                    <p class="font-bold text-sm text-slate-800 mt-0.5">Jakarta, Indonesia</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 p-4 md:p-5 rounded-2xl bg-zinc-50 border border-zinc-100">
                                <div class="h-12 w-12 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 shrink-0">
                                    <span class="material-symbols-outlined">alternate_email</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Email Resmi</p>
                                    <p class="font-bold text-sm text-slate-800 mt-0.5">support@volunteerhub.org</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>
</div>
@endsection
