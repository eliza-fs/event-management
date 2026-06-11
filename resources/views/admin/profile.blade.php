@extends('layouts.admin')
@section('title', 'Profil Organisasi | VolunteerHub')

@push('styles')
<style>#editModal.show { display: flex; } .fill-1 { font-variation-settings: 'FILL' 1; }</style>
@endpush

@section('content')
<div id="editModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="bg-white p-6 md:p-8 rounded-[40px] max-w-lg w-full shadow-2xl border border-gray-100 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-black text-[#263238]">Ubah Profil Organisasi</h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Nama Organisasi</label>
                <input type="text" name="org_name" value="{{ old('org_name', $org->org_name) }}" required
                       class="w-full rounded-2xl border-gray-200 bg-[#f6f8f6] p-3 outline-none focus:ring-2 focus:ring-[#2F7F79] border"/>
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full rounded-2xl border-gray-200 bg-[#f6f8f6] p-3 outline-none focus:ring-2 focus:ring-[#2F7F79] border">{{ old('description', $org->description) }}</textarea>
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $org->email) }}"
                       class="w-full rounded-2xl border-gray-200 bg-[#f6f8f6] p-3 outline-none focus:ring-2 focus:ring-[#2F7F79] border"/>
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $org->phone) }}"
                       class="w-full rounded-2xl border-gray-200 bg-[#f6f8f6] p-3 outline-none focus:ring-2 focus:ring-[#2F7F79] border"/>
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Alamat</label>
                <input type="text" name="address" value="{{ old('address', $org->address) }}"
                       class="w-full rounded-2xl border-gray-200 bg-[#f6f8f6] p-3 outline-none focus:ring-2 focus:ring-[#2F7F79] border"/>
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Logo / Gambar Organisasi</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-[#2F7F79]/10 file:text-[#2F7F79] file:font-bold"/>
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal()" class="px-6 py-3 rounded-2xl border border-gray-200 font-bold text-gray-500 hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-6 py-3 rounded-2xl bg-[#2F7F79] text-white font-black shadow-lg hover:opacity-90">Simpan</button>
            </div>
        </form>
    </div>
</div>

<main class="flex-1 flex justify-center py-8 px-4 lg:px-20">
    <div class="max-w-[1200px] w-full flex flex-col md:flex-row gap-8">

        <aside class="w-full md:w-64 flex flex-col gap-6">
            <div class="bg-white border border-gray-200 rounded-[32px] p-6 flex flex-col gap-6 shadow-sm">
                <div class="flex flex-col items-center text-center gap-3">
                    <x-avatar
                        :name="$org->org_name"
                        :image-url="$org->image_url"
                        :has-image="$org->has_stored_image"
                        :seed="$org->org_name"
                        class="size-20 rounded-full border-4 border-[#f6f8f6] shadow-md"
                        text-class="text-xl font-bold text-white"
                    />
                    <div>
                        <h1 class="text-[#263238] text-lg font-black">{{ $org->org_name }}</h1>
                        <p class="text-[#2F7F79] text-xs font-bold mt-2 px-3 py-1 bg-[#2F7F79]/10 rounded-full inline-block">
                            {{ $org->category->name ?? 'Organisasi' }}
                        </p>
                    </div>
                </div>
                <nav class="flex flex-col gap-1">
                    <a class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold bg-[#2F7F79]/5 text-[#2F7F79]" href="{{ route('admin.profile') }}">
                        <span class="material-symbols-outlined text-[22px]">manage_accounts</span>
                        <span class="text-sm">Profil Akun</span>
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[#263238] hover:bg-gray-50 font-bold transition-all" href="{{ route('admin.payment-methods.index') }}">
                        <span class="material-symbols-outlined text-[22px]">payments</span>
                        <span class="text-sm">Metode Pembayaran</span>
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[#263238] hover:bg-gray-50 font-bold transition-all" href="{{ route('admin.forgot-password') }}">
                        <span class="material-symbols-outlined text-[22px]">lock_reset</span>
                        <span class="text-sm">Lupa Kata Sandi</span>
                    </a>
                    <div class="my-3 border-t border-gray-50"></div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-red-600 hover:bg-red-50 transition-all font-bold cursor-pointer">
                            <span class="material-symbols-outlined text-[22px]">logout</span>
                            <span class="text-sm">Keluar Panel</span>
                        </button>
                    </form>
                </nav>
            </div>
            <button type="button" onclick="openModal()"
                    class="w-full flex items-center justify-center gap-2 rounded-2xl h-14 bg-[#2F7F79] text-white font-black shadow-xl hover:scale-[1.02] transition-all">
                <span class="material-symbols-outlined">settings</span> Pengaturan Akun
            </button>
        </aside>

        <div class="flex-1 flex flex-col gap-6">
            <section class="bg-white border border-gray-200 rounded-[40px] p-8 shadow-sm">
                <h2 class="text-[#263238] text-3xl font-black tracking-tight">{{ $org->org_name }}</h2>
                <p class="text-gray-500 text-sm mt-1">{{ $org->category->name ?? '-' }} • {{ $org->address ?? 'Indonesia' }}</p>
                @if($org->description)
                    <p class="text-gray-600 text-sm mt-4 leading-relaxed">{{ $org->description }}</p>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-10 pt-8 border-t border-gray-50">
                    <div class="flex flex-col gap-1 p-6 rounded-[24px] bg-[#2F7F79]/5 border border-[#2F7F79]/10">
                        <p class="text-[#2F7F79] text-3xl font-black">{{ $stats['managed_activities'] }}</p>
                        <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest">Kegiatan Dikelola</p>
                    </div>
                    <div class="flex flex-col gap-1 p-6 rounded-[24px] bg-blue-50 border border-blue-100">
                        <p class="text-blue-600 text-3xl font-black">{{ $stats['processed_volunteers'] }}</p>
                        <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest">Relawan Disetujui</p>
                    </div>
                    <div class="flex flex-col gap-1 p-6 rounded-[24px] bg-orange-50 border border-orange-100">
                        <p class="text-orange-600 text-3xl font-black">{{ $stats['pending_registrations'] }}</p>
                        <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest">Pendaftaran Pending</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>

@push('scripts')
<script>
    function openModal() { document.getElementById('editModal').classList.add('show'); }
    function closeModal() { document.getElementById('editModal').classList.remove('show'); }
    @if(session('success')) alert(@json(session('success'))); @endif
</script>
@endpush
@endsection
