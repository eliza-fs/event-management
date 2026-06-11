@extends('layouts.app')
@section('title', 'Kegiatan Selesai | VolunteerHub')

@section('content')
<div class="flex flex-1">
    {{-- Sidebar --}}
    <aside class="w-64 border-r border-gray-200 bg-white p-4 hidden lg:flex flex-col">
        <div class="px-3 py-2 mb-4">
            <h1 class="text-[#131613] text-base font-bold">Portal Relawan</h1>
            <p class="text-[#6b806c] text-xs">Kelola kontribusimu</p>
        </div>
        <nav class="flex flex-col gap-1">
            <a href="{{ route('user.activities.status') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#6b806c] hover:bg-gray-100 transition-all {{ request()->routeIs('user.activities.status') ? 'bg-[#2F7F79]/10 text-[#2F7F79]' : '' }}">
                <span class="material-symbols-outlined">calendar_today</span>
                <p class="text-sm font-medium">Aktivitas Saya</p>
            </a>
            <a href="{{ route('user.payment.history') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#6b806c] hover:bg-gray-100 transition-all {{ request()->routeIs('user.payment.history') ? 'bg-[#2F7F79]/10 text-[#2F7F79]' : '' }}">
                <span class="material-symbols-outlined">payments</span>
                <p class="text-sm font-medium">Riwayat Pembayaran</p>
            </a>
            <a href="{{ route('user.past.events') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('user.past.events') ? 'bg-[#2F7F79]/10 text-[#2F7F79] font-bold' : 'text-[#6b806c] hover:bg-gray-100' }}">
                <span class="material-symbols-outlined">history</span>
                <p class="text-sm">Kegiatan Selesai</p>
            </a>
            <div class="h-px bg-gray-100 my-4 mx-3"></div>
            <a href="{{ route('terms') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#6b806c] hover:bg-gray-100 transition-all">
                <span class="material-symbols-outlined">help_outline</span>
                <p class="text-sm font-medium">Bantuan & Legal</p>
            </a>
        </nav>
    </aside>

    {{-- Konten --}}
    <main class="flex-1 p-8 bg-[#f5f7fa]">
        <div class="max-w-5xl mx-auto">
            <h1 class="text-3xl font-black mb-2">Kegiatan Selesai</h1>
            <p class="text-[#6b806c] mb-6">Berikut adalah kontribusi sosial yang telah berhasil Anda selesaikan.</p>

            <div class="space-y-4">
                @forelse($registrations as $registration)
                    <div class="p-6 bg-white rounded-xl border border-gray-200 flex items-center justify-between">
                        <div class="flex gap-4">
                            <div class="size-12 bg-[#2F7F79]/10 text-[#2F7F79] flex items-center justify-center rounded-xl">
                                <span class="material-symbols-outlined">park</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold">{{ $registration->event->title ?? '-' }}</h3>
                                <p class="text-sm text-gray-500">
                                    Selesai: {{ $registration->event->end_date?->format('d M Y') ?? '-' }}
                                    • {{ $registration->event->organization->org_name ?? '-' }}
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-teal-50 text-teal-700 text-xs font-bold rounded-full uppercase">
                            Selesai
                        </span>
                    </div>
                @empty
                    <div class="p-8 text-center bg-white rounded-xl border border-dashed border-gray-300 text-gray-500">
                        <span class="material-symbols-outlined text-4xl mb-2 block">history_toggle_off</span>
                        <p>Belum ada kegiatan yang diselesaikan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
</div>
@endsection
