@extends('layouts.admin')
@section('title', 'Manajemen Pembayaran | VolunteerHub')

@section('content')
<div x-data="{
    showProof: false, currentImg: '',
    searchQuery: '', statusFilter: 'Semua',
    payments: @js($paymentsJson),
    get filteredPayments() {
        return this.payments.filter(p => {
            const matchStatus = this.statusFilter === 'Semua' || p.status === this.statusFilter;
            const q = this.searchQuery.toLowerCase();
            const matchSearch = !q || p.name.toLowerCase().includes(q) || p.id.toLowerCase().includes(q)
                || (p.eventTitle || '').toLowerCase().includes(q);
            return matchStatus && matchSearch;
        });
    },
    updatePayment(dbId, action) {
        const form = document.getElementById('payment-action-' + action + '-' + dbId);
        if (form) form.submit();
    }
}">

<main class="max-w-[1200px] mx-auto w-full px-4 md:px-6 py-10 flex-grow">
    <h1 class="text-2xl md:text-3xl font-black tracking-tight mb-8">Verifikasi Pembayaran</h1>

    <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-[#2F7F79]">payments</span>
            <span class="font-bold text-sm text-slate-600">Filter:</span>
        </div>
        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto flex-grow justify-end">
            <select x-model="statusFilter" class="rounded-xl border-zinc-200 text-sm focus:ring-[#2F7F79] w-full md:w-48">
                <option value="Semua">Semua Status</option>
                <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                <option value="Berhasil">Berhasil</option>
                <option value="Ditolak">Ditolak</option>
            </select>
            <input type="text" x-model="searchQuery" placeholder="Cari nama atau ID..."
                   class="rounded-xl border-zinc-200 text-sm focus:ring-[#2F7F79] w-full md:w-64"/>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-zinc-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[900px]">
                <thead class="bg-slate-50 border-b border-zinc-100 text-xs font-black uppercase text-slate-400 tracking-widest">
                    <tr>
                        <th class="px-8 py-4">Info Pembayar</th>
                        <th class="px-8 py-4">Nama Kegiatan</th>
                        <th class="px-8 py-4">Nominal & Metode</th>
                        <th class="px-8 py-4">Bukti</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50">
                    <template x-for="pay in filteredPayments" :key="pay.id">
                        <tr class="hover:bg-slate-50 transition-all">
                            <td class="px-8 py-6">
                                <p class="font-bold" x-text="pay.name"></p>
                                <p class="text-[10px] text-slate-400 font-mono" x-text="pay.id"></p>
                            </td>
                            <td class="px-8 py-6 text-sm font-medium text-slate-600" x-text="pay.eventTitle"></td>
                            <td class="px-8 py-6 text-sm">
                                <p class="font-black text-[#2F7F79]" x-text="pay.amount"></p>
                                <p class="text-xs text-slate-500" x-text="pay.method"></p>
                            </td>
                            <td class="px-8 py-6">
                                <button type="button" @click="if(pay.proofImg) { currentImg = pay.proofImg; showProof = true; }"
                                        :disabled="!pay.proofImg"
                                        :class="!pay.proofImg ? 'opacity-40 cursor-not-allowed' : 'hover:underline'"
                                        class="flex items-center gap-2 text-xs font-bold text-blue-600">
                                    <span class="material-symbols-outlined text-sm">image</span> Lihat Bukti
                                </button>
                            </td>
                            <td class="px-8 py-6">
                                <span :class="{
                                    'bg-blue-50 text-blue-600': pay.status === 'Menunggu Verifikasi',
                                    'bg-green-100 text-green-600': pay.status === 'Berhasil',
                                    'bg-red-100 text-red-600': pay.status === 'Ditolak'
                                }" class="px-3 py-1 text-[10px] font-black uppercase rounded-lg" x-text="pay.status"></span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex justify-center gap-2">
                                    <form :id="'payment-action-approve-' + pay.dbId" method="POST" :action="'{{ url('/admin/payments') }}/' + pay.dbId + '/approve'" class="inline">
                                        @csrf
                                        <button type="button" @click="updatePayment(pay.dbId, 'approve')"
                                            :disabled="pay.status === 'Berhasil'"
                                            :class="pay.status === 'Berhasil' ? 'opacity-40 cursor-not-allowed' : 'hover:scale-105'"
                                            class="px-4 py-2 bg-[#2F7F79] text-white rounded-xl text-xs font-black transition-all">BERHASIL</button>
                                    </form>
                                    <form :id="'payment-action-reject-' + pay.dbId" method="POST" :action="'{{ url('/admin/payments') }}/' + pay.dbId + '/reject'" class="inline">
                                        @csrf
                                        <button type="button" @click="updatePayment(pay.dbId, 'reject')"
                                            :disabled="pay.status === 'Ditolak'"
                                            :class="pay.status === 'Ditolak' ? 'opacity-40 cursor-not-allowed' : 'hover:scale-105'"
                                            class="px-4 py-2 bg-red-600 text-white rounded-xl text-xs font-black transition-all">DITOLAK</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="filteredPayments.length === 0">
                        <tr><td colspan="6" class="text-center py-12 text-slate-400 italic text-sm">Tidak ada data pembayaran.</td></tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Bukti --}}
    <div x-show="showProof" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showProof = false"></div>
        <div class="relative bg-white rounded-[40px] p-5 shadow-2xl max-w-sm w-full">
            <div class="flex justify-between items-center mb-4 px-2">
                <span class="font-black text-sm">Bukti Pembayaran</span>
                <button @click="showProof = false" class="material-symbols-outlined text-slate-400 hover:text-slate-600">close</button>
            </div>
            <img :src="currentImg" class="w-full h-auto max-h-[60vh] object-contain rounded-3xl border shadow-inner bg-slate-50" alt="Bukti Transfer"/>
            <button @click="showProof = false" class="w-full mt-4 py-3 bg-[#2F7F79] text-white rounded-2xl font-black hover:scale-[1.02]">OK, Sudah Cek</button>
        </div>
    </div>

</main>
</div>
@endsection
