@extends('layouts.app')
@section('title', 'Status Refund | VolunteerHub')

@section('content')
<main class="flex-grow max-w-6xl mx-auto py-10 px-6 w-full">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-4xl md:text-5xl font-black">Status Refund</h1>
            <p class="text-gray-500 mt-2">Pantau dan kelola pengajuan refund kegiatan Anda.</p>
        </div>
        <a href="{{ route('refund.request') }}"
           class="inline-block bg-[#2F7F79] text-white px-6 py-3 rounded-xl font-bold hover:bg-[#256b66] transition whitespace-nowrap">
            Ajukan Refund
        </a>
    </div>

    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <p class="text-sm text-gray-500 font-semibold">Total Pengajuan</p>
            <h2 class="text-4xl font-black mt-2">{{ $totalRefunds ?? 0 }}</h2>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <p class="text-sm text-gray-500 font-semibold">Refund Diproses</p>
            <h2 class="text-4xl font-black mt-2">Rp{{ number_format($totalProcessed ?? 0, 0, ',', '.') }}</h2>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <p class="text-sm text-gray-500 font-semibold">Refund Disetujui</p>
            <h2 class="text-4xl font-black mt-2">Rp{{ number_format($totalApproved ?? 0, 0, ',', '.') }}</h2>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border overflow-x-auto">
        <table class="w-full min-w-[700px]">
            <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="px-6 py-4 text-sm text-gray-600">Nama Kegiatan</th>
                    <th class="px-6 py-4 text-sm text-gray-600">Jumlah</th>
                    <th class="px-6 py-4 text-sm text-gray-600">Tanggal</th>
                    <th class="px-6 py-4 text-sm text-gray-600">Status</th>
                    <th class="px-6 py-4 text-sm text-gray-600">Bukti Transfer</th>
                </tr>
            </thead>
            <tbody>
                @forelse($refunds as $refund)
                    <tr class="border-t">
                        <td class="px-6 py-5">
                            <div class="font-bold">{{ $refund->payment->eventRegistration->event->title ?? '-' }}</div>
                            <div class="text-sm text-gray-500">#REF-{{ $refund->id }}</div>
                        </td>
                        <td class="px-6 py-5">Rp{{ number_format($refund->amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-5">{{ $refund->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-5">
                            @if($refund->status === 'approved')
                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm font-bold">Berhasil</span>
                            @elseif($refund->status === 'pending')
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-bold">Diproses</span>
                            @else
                                <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-sm font-bold">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            @php($transferUrl = \App\Support\StorageImage::url($refund->transfer_proof))
                            @if($transferUrl)
                                <a href="{{ $transferUrl }}" target="_blank"
                                   class="text-[#2F7F79] font-bold text-sm hover:underline flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">image</span> Lihat Bukti
                                </a>
                            @else
                                <span class="text-gray-400 text-sm">Menunggu admin</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="border-t">
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">Belum ada pengajuan refund.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection
