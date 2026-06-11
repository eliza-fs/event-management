@extends('layouts.admin')
@section('title', 'Manajemen Kegiatan | VolunteerHub')

@section('content')
<div x-data="{
    showForm: false, editMode: false,
    selectedType: 'All', selectedCategory: 'All', searchQuery: '',
    formData: { id: null, title: '', description: '', cat: 'Volunteer', type: 'Onsite', quota: 0, registered: 0, price: 0, location: '', start_date: '', end_date: '' },
    events: @js($eventsJson),
    categoryOptions: @js($categoryOptions),
    typeOptions: @js($eventTypes->pluck('name')),
    get filteredEvents() {
        return this.events.filter(e => {
            const matchType = this.selectedType === 'All' || e.type === this.selectedType;
            const matchCat = this.selectedCategory === 'All' || e.cat === this.selectedCategory;
            const q = this.searchQuery.toLowerCase();
            const matchSearch = !q || e.title.toLowerCase().includes(q) || (e.description || '').toLowerCase().includes(q);
            return matchType && matchCat && matchSearch;
        });
    },
    openAddForm() {
        this.editMode = false;
        this.formData = { id: null, title: '', description: '', cat: this.categoryOptions[0] || 'Volunteer', type: 'Onsite', quota: 10, registered: 0, price: 0, location: '', start_date: '', end_date: '' };
        this.showForm = true;
    },
    openEditForm(event) { this.editMode = true; this.formData = { ...event }; this.showForm = true; },
    init() {
        const params = new URLSearchParams(window.location.search);
        const editId = params.get('edit');
        if (editId) {
            const event = this.events.find(e => String(e.id) === String(editId));
            if (event) this.openEditForm(event);
        }
    },
    saveEvent() {
        if (!this.formData.title.trim()) { alert('Judul wajib diisi!'); return; }
        if (this.formData.quota < (this.editMode ? this.formData.registered : 1)) { alert('Kuota tidak boleh kurang dari peserta aktif!'); return; }
        this.$refs.eventForm.submit();
    },
    deleteEvent(id) {
        if (!confirm('Hapus kegiatan ini?')) return;
        const form = document.getElementById('delete-event-form-' + id);
        if (form) form.submit();
    }
}" x-init="init()">

<main class="max-w-[1200px] mx-auto w-full px-4 md:px-6 py-10">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <h1 class="text-2xl md:text-3xl font-black tracking-tight">Pengaturan Kegiatan</h1>
        <button @click="openAddForm()"
                class="w-full md:w-auto bg-[#2F7F79] text-white px-6 py-3 rounded-xl font-black shadow-lg hover:scale-105 transition-all flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">add</span> Tambah Kegiatan
        </button>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm mb-8 flex flex-wrap gap-4 items-center">
        <div class="flex items-center gap-2"><span class="material-symbols-outlined text-[#2F7F79]">filter_alt</span><span class="font-bold text-sm">Filter:</span></div>
        <select x-model="selectedType" class="rounded-xl border-zinc-200 text-sm focus:ring-[#2F7F79]">
            <option value="All">Semua Tipe</option>
            <template x-for="t in typeOptions" :key="t"><option :value="t" x-text="t"></option></template>
        </select>
        <select x-model="selectedCategory" class="rounded-xl border-zinc-200 text-sm focus:ring-[#2F7F79]">
            <option value="All">Semua Kategori</option>
            <template x-for="cat in categoryOptions" :key="cat">
                <option :value="cat" x-text="cat"></option>
            </template>
        </select>
        <input type="text" x-model="searchQuery" placeholder="Cari judul atau deskripsi..." class="rounded-xl border-zinc-200 text-sm flex-grow md:max-w-xs"/>
    </div>

    <div class="bg-white rounded-3xl border border-zinc-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[800px]">
                <thead class="bg-slate-50 border-b border-zinc-100 text-xs font-black uppercase text-slate-400 tracking-widest">
                    <tr>
                        <th class="px-8 py-4">Kegiatan</th>
                        <th class="px-8 py-4">Kuota</th>
                        <th class="px-8 py-4">Biaya</th>
                        <th class="px-8 py-4">Deskripsi</th>
                        <th class="px-8 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50">
                    <template x-for="event in filteredEvents" :key="event.id">
                        <tr class="hover:bg-slate-50 transition-all">
                            <td class="px-8 py-6">
                                <p class="font-bold" x-text="event.title"></p>
                                <div class="flex gap-2 mt-1">
                                    <span class="text-[9px] bg-[#2F7F79]/10 text-[#2F7F79] px-2 py-0.5 rounded font-black uppercase" x-text="event.cat"></span>
                                    <span class="text-[9px] bg-slate-100 px-2 py-0.5 rounded font-black uppercase" x-text="event.type"></span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-sm font-bold text-slate-600">
                                <span x-text="event.registered"></span>/<span x-text="event.quota"></span>
                            </td>
                            <td class="px-8 py-6 text-sm font-bold text-[#2F7F79]"
                                x-text="event.price == 0 ? 'Gratis' : 'Rp' + Number(event.price).toLocaleString('id-ID')"></td>
                            <td class="px-8 py-6 text-sm font-medium text-slate-500 max-w-xs truncate" x-text="event.description"></td>
                            <td class="px-8 py-6">
                                <div class="flex justify-center gap-2">
                                    <button @click="openEditForm(event)" class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition-all">
                                        <span class="material-symbols-outlined">edit</span>
                                    </button>
                                    <form :id="'delete-event-form-' + event.id" method="POST" :action="'{{ url('/admin/events') }}/' + event.id" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" @click="deleteEvent(event.id)" class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition-all">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="filteredEvents.length === 0">
                        <tr><td colspan="5" class="text-center py-10 text-slate-400 italic text-sm">Tidak ada kegiatan.</td></tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="showForm" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showForm = false"></div>
        <div class="relative bg-white rounded-[40px] w-full max-w-2xl p-6 md:p-8 shadow-2xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-2xl font-black mb-6" x-text="editMode ? 'Edit Kegiatan' : 'Tambah Kegiatan Baru'"></h2>
            <form x-ref="eventForm" method="POST" enctype="multipart/form-data"
                  :action="editMode ? '{{ url('/admin/events') }}/' + formData.id : '{{ route('admin.events.store') }}'"
                  class="space-y-6">
                @csrf
                <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-sm font-bold">Judul Kegiatan</label>
                        <input type="text" name="title" x-model="formData.title" required class="w-full rounded-2xl border border-zinc-200 p-3 outline-none focus:ring-2 focus:ring-[#2F7F79]"/>
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-sm font-bold">Deskripsi</label>
                        <textarea name="description" x-model="formData.description" rows="3" placeholder="Jelaskan kegiatan..."
                                  class="w-full rounded-2xl border border-zinc-200 p-3 outline-none focus:ring-2 focus:ring-[#2F7F79]"></textarea>
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-sm font-bold">Gambar Kegiatan</label>
                        <input type="file" name="image" accept="image/*" class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-[#2F7F79]/10 file:text-[#2F7F79] file:font-bold"/>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold">Kategori</label>
                        <select name="cat" x-model="formData.cat" class="w-full rounded-2xl border border-zinc-200 p-3 outline-none focus:ring-2 focus:ring-[#2F7F79]">
                            <template x-for="cat in categoryOptions" :key="cat">
                                <option :value="cat" x-text="cat"></option>
                            </template>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold">Tipe Pelaksanaan</label>
                        <select name="type" x-model="formData.type" class="w-full rounded-2xl border border-zinc-200 p-3 outline-none focus:ring-2 focus:ring-[#2F7F79]">
                            <template x-for="t in typeOptions" :key="t">
                                <option :value="t" x-text="t"></option>
                            </template>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold">Kuota Peserta</label>
                        <input type="number" name="quota" x-model.number="formData.quota" :min="editMode ? formData.registered : 1" required
                               class="w-full rounded-2xl border border-zinc-200 p-3 outline-none focus:ring-2 focus:ring-[#2F7F79]"/>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold">Biaya (Rp)</label>
                        <input type="number" name="price" x-model.number="formData.price" min="0" placeholder="0"
                               class="w-full rounded-2xl border border-zinc-200 p-3 outline-none focus:ring-2 focus:ring-[#2F7F79]"/>
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-sm font-bold">Lokasi</label>
                        <input type="text" name="location" x-model="formData.location"
                               class="w-full rounded-2xl border border-zinc-200 p-3 outline-none focus:ring-2 focus:ring-[#2F7F79]"/>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold">Mulai</label>
                        <input type="datetime-local" name="start_date" x-model="formData.start_date"
                               class="w-full rounded-2xl border border-zinc-200 p-3 outline-none focus:ring-2 focus:ring-[#2F7F79]"/>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold">Selesai</label>
                        <input type="datetime-local" name="end_date" x-model="formData.end_date"
                               class="w-full rounded-2xl border border-zinc-200 p-3 outline-none focus:ring-2 focus:ring-[#2F7F79]"/>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row gap-4 pt-6">
                    <button type="button" @click="showForm = false" class="order-2 md:order-1 flex-1 py-4 bg-slate-100 rounded-2xl font-black hover:bg-slate-200">Batal</button>
                    <button type="button" @click="saveEvent()" class="order-1 md:order-2 flex-1 py-4 bg-[#2F7F79] text-white rounded-2xl font-black shadow-lg hover:scale-[1.02]">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</main>
</div>
@endsection
