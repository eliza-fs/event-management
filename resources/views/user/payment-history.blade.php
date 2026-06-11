@extends('layouts.app')
@section('title', 'Riwayat Pembayaran | VolunteerHub')

@section('content')
<div class="flex flex-grow flex-col lg:flex-row bg-[#f5f7fa]">

    {{-- Sidebar --}}
    <aside class="w-full lg:w-64 border-b lg:border-b-0 lg:border-r border-gray-200 bg-white p-4 flex flex-col">
        <div class="flex flex-col gap-4">
            <div class="px-3 py-2 hidden lg:block">
                <h1 class="text-[#131613] text-base font-bold">Portal Relawan</h1>
                <p class="text-[#6b806c] text-xs">Kelola kontribusimu</p>
            </div>
            <nav class="flex flex-row lg:flex-col overflow-x-auto lg:overflow-visible gap-1 pb-2 lg:pb-0">
                <a href="{{ route('user.profile.history') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#6b806c] hover:bg-gray-100 transition-all whitespace-nowrap text-sm">
                    <span class="material-symbols-outlined text-lg">dashboard</span>
                    <p class="font-medium">Ringkasan</p>
                </a>
                <a href="{{ route('user.activities.status') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#6b806c] hover:bg-gray-100 transition-all whitespace-nowrap text-sm">
                    <span class="material-symbols-outlined text-lg">calendar_today</span>
                    <p class="font-medium">Aktivitas Saya</p>
                </a>
                <a href="{{ route('user.payment.history') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary whitespace-nowrap text-sm">
                    <span class="material-symbols-outlined text-lg">payments</span>
                    <p class="font-bold">Riwayat Pembayaran</p>
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

    {{-- Konten Utama --}}
    <main class="flex-1 p-4 md:p-8">
        <div class="max-w-5xl mx-auto">
            <h1 class="text-2xl md:text-3xl font-black mb-1 md:mb-2">Riwayat Pembayaran</h1>
            <p class="text-xs md:text-sm text-[#6b806c] mb-6">Pantau status transaksi event Anda.</p>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Transaksi</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Metode</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-xs md:text-sm">
                            @if(isset($payments) && count($payments) > 0)
                                @foreach($payments as $payment)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $payment->transaction_name }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $payment->method ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $payment->date_formatted }}</td>
                                    <td class="px-6 py-4 font-bold text-primary">
                                        {{ $payment->amount == 0 ? 'Gratis' : 'Rp'.number_format($payment->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($payment->status === 'success')
                                            <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Lunas</span>
                                        @elseif($payment->status === 'pending')
                                            <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">Menunggu</span>
                                        @else
                                            <span class="px-2.5 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">Gagal</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-sm">Belum ada riwayat pembayaran.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
