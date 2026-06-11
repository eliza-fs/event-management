@extends('layouts.admin')
@section('title', 'Metode Pembayaran | VolunteerHub')

@section('content')
<div x-data="{
    showForm: false, editMode: false,
    formData: { id: null, eventId: '', method: '', accountNumber: '', accountOwner: '' },
    methods: @js($methodsJson),
    openAdd() {
        this.editMode = false;
        this.formData = { id: null, eventId: '', method: '', accountNumber: '', accountOwner: '' };
        this.showForm = true;
    },
    openEdit(item) {
        this.editMode = true;
        this.formData = { id: item.id, eventId: item.eventId, method: item.method, accountNumber: item.accountNumber, accountOwner: item.accountOwner };
        this.showForm = true;
    },
    saveForm() { this.$refs.methodForm.submit(); },
    deleteMethod(id) {
        if (!confirm('Hapus metode pembayaran ini?')) return;
        document.getElementById('delete-method-' + id)?.submit();
    }
}">

<main class="max-w-[1200px] mx-auto w-full px-4 md:px-6 py-10">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <h1 class="text-2xl md:text-3xl font-black tracking-tight">Metode Pembayaran</h1>
        <button @click="openAdd()" class="bg-[#2F7F79] text-white px-6 py-3 rounded-xl font-black shadow-lg hover:scale-105 transition-all flex items-center gap-2">
            <span class="material-symbols-outlined">add</span> Tambah Metode
        </button>
    </div>

    <div class="bg-white rounded-3xl border border-zinc-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[800px]">
                <thead class="bg-slate-50 border-b border-zinc-100 text-xs font-black uppercase text-slate-400 tracking-widest">
                    <tr>
                        <th class="px-8 py-4">Kegiatan</th>
                        <th class="px-8 py-4">Metode</th>
                        <th class="px-8 py-4">No. Rekening</th>
                        <th class="px-8 py-4">Pemilik Rekening</th>
                        <th class="px-8 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50">
                    <template x-for="m in methods" :key="m.id">
                        <tr class="hover:bg-slate-50">
                            <td class="px-8 py-6 font-bold" x-text="m.eventTitle"></td>
                            <td class="px-8 py-6" x-text="m.method"></td>
                            <td class="px-8 py-6 font-mono text-sm" x-text="m.accountNumber"></td>
                            <td class="px-8 py-6" x-text="m.accountOwner"></td>
                            <td class="px-8 py-6">
                                <div class="flex justify-center gap-2">
                                    <button @click="openEdit(m)" class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg">
                                        <span class="material-symbols-outlined">edit</span>
                                    </button>
                                    <form :id="'delete-method-' + m.id" method="POST" :action="'{{ url('/admin/payment-methods') }}/' + m.id" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" @click="deleteMethod(m.id)" class="text-red-600 hover:bg-red-50 p-2 rounded-lg">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="methods.length === 0">
                        <tr><td colspan="5" class="text-center py-12 text-slate-400 italic text-sm">Belum ada metode pembayaran.</td></tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="showForm" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showForm = false"></div>
        <div class="relative bg-white rounded-[40px] w-full max-w-lg p-8 shadow-2xl">
            <h2 class="text-2xl font-black mb-6" x-text="editMode ? 'Edit Metode' : 'Tambah Metode'"></h2>
            <form x-ref="methodForm" method="POST"
                  :action="editMode ? '{{ url('/admin/payment-methods') }}/' + formData.id : '{{ route('admin.payment-methods.store') }}'"
                  class="space-y-4">
                @csrf
                <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
                <div>
                    <label class="text-sm font-bold">Kegiatan</label>
                    <select name="event_id" x-model="formData.eventId" required class="w-full rounded-2xl border p-3 mt-1">
                        <option value="">Pilih kegiatan berbayar</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-bold">Metode</label>
                    <input type="text" name="method" x-model="formData.method" required placeholder="BCA, GoPay, dll"
                           class="w-full rounded-2xl border p-3 mt-1"/>
                </div>
                <div>
                    <label class="text-sm font-bold">Nomor Rekening / Akun</label>
                    <input type="text" name="account_number" x-model="formData.accountNumber" required
                           class="w-full rounded-2xl border p-3 mt-1"/>
                </div>
                <div>
                    <label class="text-sm font-bold">Pemilik Rekening</label>
                    <input type="text" name="account_owner" x-model="formData.accountOwner" required
                           class="w-full rounded-2xl border p-3 mt-1"/>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showForm = false" class="flex-1 py-3 bg-slate-100 rounded-2xl font-black">Batal</button>
                    <button type="button" @click="saveForm()" class="flex-1 py-3 bg-[#2F7F79] text-white rounded-2xl font-black">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</main>
</div>
@endsection
