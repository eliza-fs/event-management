@extends('layouts.app')
@section('title', 'Aktivitas Saya | VolunteerHub')

@section('content')
<div class="flex flex-1 flex-col lg:flex-row flex-grow bg-[#f5f7fa]" x-data="{ currentTab: 'semua' }">

    <aside class="w-full lg:w-64 border-b lg:border-b-0 lg:border-r border-gray-200 bg-white p-4 flex flex-col">
        <div class="flex flex-col gap-4">
            <div class="px-3 py-2 hidden lg:block">
                <h1 class="text-[#131613] text-base font-bold">Portal Relawan</h1>
                <p class="text-[#6b806c] text-xs">Kelola kontribusimu</p>
            </div>
            <nav class="flex flex-row lg:flex-col overflow-x-auto lg:overflow-visible gap-1 pb-2 lg:pb-0">
                <a href="{{ route('user.activities.status') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary whitespace-nowrap text-sm">
                    <span class="material-symbols-outlined text-lg">calendar_today</span>
                    <p class="font-bold">Aktivitas Saya</p>
                </a>
                <a href="{{ route('user.payment.history') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#6b806c] hover:bg-gray-100 transition-all whitespace-nowrap text-sm">
                    <span class="material-symbols-outlined text-lg">payments</span>
                    <p class="font-medium">Riwayat Pembayaran</p>
                </a>
                <a href="{{ route('user.past.events') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#6b806c] hover:bg-gray-100 transition-all whitespace-nowrap text-sm">
                    <span class="material-symbols-outlined text-lg">history</span>
                    <p class="font-medium">Kegiatan Selesai</p>
                </a>
                <div class="hidden lg:block h-px bg-gray-100 my-2 mx-3"></div>
                <a href="{{ route('terms') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#6b806c] hover:bg-gray-100 transition-all whitespace-nowrap text-sm">
                    <span class="material-symbols-outlined text-lg">help_outline</span>
                    <p class="font-medium">Bantuan & Legal</p>
                </a>
            </nav>
        </div>
    </aside>

    <main class="flex-1 p-4 md:p-8">
        <div class="max-w-5xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black mb-1">Aktivitas & Status Saya</h1>
                    <p class="text-xs md:text-sm text-[#6b806c]">Pantau status pendaftaran dan partisipasi Anda.</p>
                </div>
                <a href="{{ route('home') }}"
                   class="text-center rounded-xl h-11 px-6 bg-primary text-white text-sm font-bold hover:opacity-90 transition-all inline-flex items-center justify-center">
                    Cari Kegiatan Lainnya
                </a>
            </div>

            <div class="flex gap-2 border-b border-gray-200 mb-6 overflow-x-auto pb-1">
                <button @click="currentTab = 'semua'"
                        :class="currentTab === 'semua' ? 'border-primary text-primary font-bold' : 'border-transparent text-gray-500'"
                        class="py-2.5 px-4 text-sm border-b-2 whitespace-nowrap">Semua</button>
                <button @click="currentTab = 'menunggu'"
                        :class="currentTab === 'menunggu' ? 'border-amber-500 text-amber-600 font-bold' : 'border-transparent text-gray-500'"
                        class="py-2.5 px-4 text-sm border-b-2 whitespace-nowrap">Menunggu Persetujuan</button>
                <button @click="currentTab = 'aktif'"
                        :class="currentTab === 'aktif' ? 'border-emerald-500 text-emerald-600 font-bold' : 'border-transparent text-gray-500'"
                        class="py-2.5 px-4 text-sm border-b-2 whitespace-nowrap">Aktif / Diterima</button>
                <button @click="currentTab = 'selesai'"
                        :class="currentTab === 'selesai' ? 'border-indigo-500 text-indigo-600 font-bold' : 'border-transparent text-gray-500'"
                        class="py-2.5 px-4 text-sm border-b-2 whitespace-nowrap">Selesai</button>
                <button @click="currentTab = 'ditolak'"
                        :class="currentTab === 'ditolak' ? 'border-red-500 text-red-600 font-bold' : 'border-transparent text-gray-500'"
                        class="py-2.5 px-4 text-sm border-b-2 whitespace-nowrap">Ditolak</button>
                <button @click="currentTab = 'dibatalkan'"
                        :class="currentTab === 'dibatalkan' ? 'border-slate-500 text-slate-600 font-bold' : 'border-transparent text-gray-500'"
                        class="py-2.5 px-4 text-sm border-b-2 whitespace-nowrap">Dibatalkan</button>
            </div>

            <div class="space-y-4">
                @forelse($registrations as $registration)
                    @php
                        $event = $registration->event;
                        $isFinished = $event && $event->end_date && $event->end_date->isPast() && $registration->status === 'approved';
                        $tabKey = match (true) {
                            $registration->status === 'cancelled' => 'dibatalkan',
                            $registration->status === 'rejected' => 'ditolak',
                            $isFinished => 'selesai',
                            $registration->status === 'approved' => 'aktif',
                            default => 'menunggu',
                        };
                        $needsPayment = $registration->status === 'pending'
                            && $event && ! $event->isFree()
                            && ($registration->payment?->status === 'pending' || ! $registration->payment);
                        $statusLabel = match (true) {
                            $registration->status === 'cancelled' => 'Dibatalkan (Refund)',
                            $isFinished => 'Selesai Berpartisipasi',
                            $registration->status === 'approved' => 'Terdaftar & Aktif',
                            $registration->status === 'rejected' => 'Ditolak',
                            $needsPayment => 'Menunggu Verifikasi Pembayaran',
                            default => 'Menunggu Persetujuan',
                        };
                    @endphp
                    <div x-show="currentTab === 'semua' || currentTab === '{{ $tabKey }}'"
                         class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm {{ $isFinished ? 'opacity-80' : '' }}">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0
                                    {{ $tabKey === 'aktif' ? 'bg-emerald-50 text-emerald-500' : ($tabKey === 'selesai' ? 'bg-indigo-50 text-indigo-500' : 'bg-amber-50 text-amber-500') }}">
                                    <span class="material-symbols-outlined text-2xl">
                                        {{ $tabKey === 'aktif' ? 'check_circle' : ($tabKey === 'selesai' ? 'workspace_premium' : 'pending_actions') }}
                                    </span>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold mb-1.5
                                        {{ $tabKey === 'aktif' ? 'bg-emerald-100 text-emerald-800' : ($tabKey === 'selesai' ? 'bg-indigo-100 text-indigo-800' : 'bg-amber-100 text-amber-800') }}">
                                        {{ $statusLabel }}
                                    </span>
                                    <h3 class="text-base font-bold text-gray-900">{{ $event->title ?? '-' }}</h3>
                                    <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">business</span>
                                        {{ $event->organization->org_name ?? '-' }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">calendar_month</span>
                                        Didaftar: {{ $registration->created_at->format('d M Y') }}
                                        @if($event?->start_date) • Pelaksanaan: {{ $event->start_date->format('d M Y') }} @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex sm:flex-col gap-2 w-full sm:w-auto justify-end">
                                @if($needsPayment)
                                    <a href="{{ route('user.payment.history') }}"
                                       class="text-center bg-primary text-white text-xs font-bold px-4 py-2 rounded-lg">Lihat Status</a>
                                @elseif($registration->status === 'cancelled')
                                    <a href="{{ route('refund.status') }}"
                                       class="text-center border border-gray-200 text-gray-700 text-xs font-bold px-4 py-2 rounded-lg">Lihat Status</a>
                                @elseif($tabKey === 'aktif')
                                    <a href="{{ route('event.detail', $event->id) }}"
                                       class="text-center border border-gray-200 text-gray-700 text-xs font-bold px-4 py-2 rounded-lg">Lihat Detail Kegiatan</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white rounded-xl border border-gray-200">
                        <span class="material-symbols-outlined text-5xl text-gray-300">layers_clear</span>
                        <p class="text-gray-500 text-sm mt-2">Belum ada aktivitas terdaftar.</p>
                        <a href="{{ route('home') }}" class="inline-block mt-4 text-sm font-bold text-primary hover:underline">Cari Kegiatan</a>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
</div>
@endsection
