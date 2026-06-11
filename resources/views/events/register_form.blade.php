{{-- resources/views/events/register_form.blade.php --}}

@extends('layouts.app')

@section('title', 'VolunteerHub - Event Registration')

@push('styles')
<style>
    /* Mengubah @apply menjadi CSS standar karena kita 
       memasukkannya ke dalam tag style biasa agar aman jika Vite belum dikonfigurasi penuh 
    */
    .payment-method.active {
        border-color: #2F7F79;
        background-color: rgba(47, 127, 121, 0.05);
        box-shadow: 0 0 0 2px #2F7F79;
    }

    .payment-method.active span {
        color: #2F7F79;
    }

    #success-modal.show {
        display: flex;
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .input-error {
        border-color: #ef4444 !important;
        background-color: #fef2f2 !important;
    }

    .error-text {
        color: #ef4444;
        font-size: 0.75rem;
        margin-top: 0.25rem;
        font-weight: 600;
        display: none;
    }

    .input-error + .error-text {
        display: block;
    }

    #copy-toast.show {
        display: block;
        animation: fadeIn 0.2s ease-out;
    }
</style>
@endpush

@section('content')
{{-- SUCCESS MODAL --}}
<div id="copy-toast" class="fixed top-6 left-1/2 -translate-x-1/2 z-[110] hidden bg-[#2F7F79] text-white text-sm font-bold px-5 py-3 rounded-xl shadow-lg">
    Nomor rekening berhasil disalin
</div>

<div id="success-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4">
    <div class="bg-white p-8 rounded-2xl max-w-md w-full text-center shadow-2xl border border-white/10">
        <div id="modal-icon-container" class="size-20 bg-[#4CAF50]/20 text-[#4CAF50] rounded-full flex items-center justify-center mx-auto mb-6">
            <span class="material-symbols-outlined text-5xl">task_alt</span>
        </div>
        <h3 id="modal-title" class="text-2xl font-bold mb-2">Registration Submitted!</h3>
        <p id="modal-message" class="text-[#6b806c] mb-8"></p>
        <button onclick="closeModal()" class="w-full bg-[#2F7F79] hover:bg-[#266964] text-white font-bold py-4 rounded-xl shadow-lg transition-all">
            Mengerti
        </button>
    </div>
</div>

<div class="relative flex flex-grow w-full flex-col overflow-x-hidden">
    {{-- MAIN --}}
    <main class="flex-1 flex justify-center py-10 px-4">
        <div class="max-w-[1024px] w-full">
            {{-- TITLE --}}
            <div class="mb-6 text-center lg:text-left">
                <p class="text-4xl font-black tracking-tighter">Registrasi Kegiatan</p>
                <p class="text-[#6b806c]">
                    Isi data dirimu untuk mengikuti <span class="text-[#2F7F79] font-bold">{{ $event->title }}</span>.
                </p>
            </div>

            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 rounded-xl p-4 text-red-700 text-sm font-bold">{{ session('error') }}</div>
            @endif
            @error('registration')
                <div class="mb-4 bg-red-50 border border-red-200 rounded-xl p-4 text-red-700 text-sm font-bold">{{ $message }}</div>
            @enderror

            <form method="POST" action="{{ route('event.register.post', $event->id) }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf
                {{-- LEFT CONTENT --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- DATA DIRI --}}
                    <section class="bg-white rounded-xl border border-[#dee3de] overflow-hidden shadow-sm">
                        <h2 class="text-[20px] font-bold px-6 py-5 border-b border-[#f1f3f1] flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#2F7F79]">person</span>
                            1. Data Diri Peserta
                        </h2>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col">
                                <p class="text-sm font-medium pb-2">Nama Lengkap</p>
                                <input id="reg-name" name="name" value="{{ old('name') }}" class="rounded-lg border-[#dee3de] bg-white h-12 px-4 focus:ring-[#2F7F79] focus:border-[#2F7F79] outline-none @error('name') border-red-500 @enderror" placeholder="Masukkan nama" />
                                @error('name')<span class="text-red-600 text-xs font-semibold mt-1">{{ $message }}</span>@enderror
                                <span class="error-text">Mohon isi nama lengkap Anda.</span>
                            </div>
                            <div class="flex flex-col">
                                <p class="text-sm font-medium pb-2">Alamat Email</p>
                                <input id="reg-email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" class="rounded-lg border-[#dee3de] bg-white h-12 px-4 focus:ring-[#2F7F79] focus:border-[#2F7F79] outline-none @error('email') border-red-500 @enderror" placeholder="example@gmail.com" />
                                @error('email')<span class="text-red-600 text-xs font-semibold mt-1">{{ $message }}</span>@enderror
                                <span class="error-text">Format email tidak valid.</span>
                            </div>
                            <div class="flex flex-col">
                                <p class="text-sm font-medium pb-2">Nomor HP</p>
                                <input id="reg-phone" name="phone" value="{{ old('phone') }}" class="rounded-lg border-[#dee3de] bg-white h-12 px-4 focus:ring-[#2F7F79] focus:border-[#2F7F79] outline-none @error('phone') border-red-500 @enderror" placeholder="08123456789" />
                                @error('phone')<span class="text-red-600 text-xs font-semibold mt-1">{{ $message }}</span>@enderror
                                <span class="error-text">Hanya angka yang diperbolehkan.</span>
                            </div>
                            <div class="flex flex-col">
                                <p class="text-sm font-medium pb-2">Usia</p>
                                <input id="reg-age" name="age" type="number" value="{{ old('age') }}" min="15" max="70" class="rounded-lg border-[#dee3de] bg-white h-12 px-4 focus:ring-[#2F7F79] focus:border-[#2F7F79] outline-none @error('age') border-red-500 @enderror" placeholder="25" />
                                @error('age')<span class="text-red-600 text-xs font-semibold mt-1">{{ $message }}</span>@enderror
                                <span class="error-text">Usia harus antara 15 dan 70 tahun.</span>
                            </div>
                            <div class="flex flex-col md:col-span-2">
                                <p class="text-sm font-medium pb-2">Lokasi (Kota, Negara)</p>
                                <input name="location" value="{{ old('location') }}" required placeholder="Jakarta, Indonesia"
                                       class="rounded-lg border-[#dee3de] bg-white h-12 px-4 focus:ring-[#2F7F79] outline-none @error('location') border-red-500 @enderror"/>
                                @error('location')<span class="text-red-600 text-xs font-semibold mt-1">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </section>

                    @if(!$event->isFree())
                    {{-- PAYMENT --}}
                    <section id="registration-section-2" class="bg-white rounded-xl border border-[#dee3de] overflow-hidden shadow-sm">
                        <h2 class="text-[20px] font-bold px-6 py-5 border-b border-[#f1f3f1] flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#2F7F79]">payments</span>
                            2. Pembayaran dan Verifikasi
                        </h2>
                        <div class="p-6 space-y-6">
                            {{-- STATUS --}}
                            <div class="flex items-center justify-between p-4 rounded-lg bg-[#FFB300]/10 border border-[#FFB300]/30">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-[#FFB300] text-3xl">pending_actions</span>
                                    <div>
                                        <p id="status-title" class="text-[#FFB300] font-bold">Status Pembayaran: Menunggu</p>
                                        <p id="status-desc" class="text-sm text-[#6b806c]">Pilih metode pembayaran.</p>
                                    </div>
                                </div>
                                <span class="bg-[#FFB300]/20 text-[#FFB300] px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Ditunda</span>
                            </div>

                            {{-- PAYMENT METHOD --}}
                            <div class="space-y-3">
                                <p class="text-base font-medium">Pilih Metode Pembayaran</p>
                                <select name="payment_method_id" id="payment_method_id" required onchange="updatePaymentDetails(this)"
                                        class="w-full rounded-lg border border-[#dee3de] h-12 px-4 focus:ring-[#2F7F79] @error('payment_method_id') border-red-500 @enderror">
                                @error('payment_method_id')<span class="text-red-600 text-xs font-semibold">{{ $message }}</span>@enderror
                                    <option value="">Pilih metode pembayaran</option>
                                    @foreach($paymentMethods as $method)
                                        <option value="{{ $method->id }}" @selected(old('payment_method_id') == $method->id)
                                                data-name="{{ $method->name }}"
                                                data-type="{{ $method->type }}"
                                                data-account-name="{{ $method->account_name }}"
                                                data-account-number="{{ $method->account_number }}"
                                                data-instructions="{{ $method->instructions }}">
                                            {{ $method->name }} ({{ ucfirst(str_replace('_', ' ', $method->type)) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- PAYMENT DETAILS --}}
                            <div id="payment-details-box" class="bg-[#f6f8f6] p-6 rounded-lg border border-[#dee3de] hidden">
                                <p class="text-xs uppercase font-bold text-[#6b806c] mb-2">Detail Pembayaran</p>
                                <div id="payment-details-content" class="text-sm space-y-1"></div>
                            </div>

                            {{-- FILE UPLOAD --}}
                            <div id="upload-section" class="space-y-3">
                                <p class="text-base font-medium">Unggah Bukti Pembayaran</p>
                                <input type="file" name="proof_image" id="file-input" class="hidden" accept="image/png,image/jpeg,.png,.jpg,.jpeg" onchange="handleFileUpload(this)">
                                @error('proof_image')<span class="text-red-600 text-xs font-semibold block">{{ $message }}</span>@enderror
                                <div onclick="document.getElementById('file-input').click()" class="border-2 border-dashed @error('proof_image') border-red-500 @else border-[#dee3de] @enderror rounded-xl p-8 flex flex-col items-center justify-center text-center gap-3 hover:border-[#2F7F79] cursor-pointer transition-colors">
                                    <span id="upload-icon" class="material-symbols-outlined text-[#2F7F79] text-4xl">cloud_upload</span>
                                    <div id="upload-text">
                                        <p class="text-sm font-bold">Tekan untuk mengunggah bukti pembayaran</p>
                                        <p class="text-xs text-[#6b806c]">PNG/JPEG (max. 5MB)</p>
                                    </div>
                                    <p id="file-name" class="text-[#2F7F79] font-bold hidden"></p>
                                </div>
                            </div>
                        </div>
                    </section>
                    @endif
                </div>

                {{-- SIDEBAR --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-24 bg-white rounded-xl border border-[#dee3de] overflow-hidden shadow-sm">
                        <x-event-image
                            :url="$event->image_url"
                            :has-image="$event->has_stored_image"
                            :alt="$event->title"
                            class="w-full h-40"
                            img-class="w-full h-40 object-cover"
                            placeholder-class="w-full h-40"
                        />
                        <div class="p-6">
                            <h3 class="text-lg font-bold mb-4">Ringkasan Registrasi</h3>
                            <div class="space-y-4 mb-6 text-sm">
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase">Nama Kegiatan</p>
                                    <p class="font-bold text-[#2F7F79]">{{ $event->title }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase">Tanggal & Waktu</p>
                                    <p class="font-medium">{{ $event->start_date?->format('d M Y') ?? '-' }}</p>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                                    <span class="font-bold">Total Biaya</span>
                                    <span id="total-fee-val" class="text-xl font-black text-[#2F7F79]">{{ $event->isFree() ? 'Gratis' : 'Rp'.number_format($event->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <button id="submit-btn" type="submit" class="w-full bg-[#2F7F79] hover:bg-[#266964] text-white font-bold py-4 rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                                Kirim
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    function updatePaymentDetails(select) {
        const box = document.getElementById('payment-details-box');
        const content = document.getElementById('payment-details-content');
        const option = select.options[select.selectedIndex];
        if (!option.value) {
            box.classList.add('hidden');
            return;
        }
        const accountNumber = option.dataset.accountNumber || '';
        content.innerHTML = `
            <div class="flex justify-between mb-1"><span>Metode:</span><span class="font-bold">${option.dataset.name}</span></div>
            <div class="flex justify-between mb-1"><span>Pemilik:</span><span class="font-bold">${option.dataset.accountName || '-'}</span></div>
            <div class="flex justify-between items-center mb-1 gap-2">
                <span>Nomor:</span>
                <span class="flex items-center gap-2 min-w-0">
                    <span class="font-bold truncate" id="account-number-value">${accountNumber || '-'}</span>
                    ${accountNumber ? `<button type="button" id="copy-account-btn" data-account="${accountNumber}" class="shrink-0 px-2.5 py-1 text-xs font-bold rounded-lg bg-[#2F7F79] text-white hover:bg-[#266964] transition-colors">Salin</button>` : ''}
                </span>
            </div>
            <p class="text-xs text-[#6b806c] mt-2">${option.dataset.instructions || ''}</p>
        `;
        const copyBtn = document.getElementById('copy-account-btn');
        if (copyBtn) {
            copyBtn.addEventListener('click', () => copyAccountNumber(copyBtn.dataset.account));
        }
        box.classList.remove('hidden');
    }

    function copyAccountNumber(accountNumber) {
        const onSuccess = () => {
            const toast = document.getElementById('copy-toast');
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 2500);
        };

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(accountNumber).then(onSuccess).catch(() => fallbackCopy(accountNumber, onSuccess));
        } else {
            fallbackCopy(accountNumber, onSuccess);
        }
    }

    function fallbackCopy(text, onSuccess) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.setAttribute('readonly', '');
        textarea.style.position = 'absolute';
        textarea.style.left = '-9999px';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            onSuccess();
        } catch (e) {
            alert('Gagal menyalin nomor rekening.');
        }
        document.body.removeChild(textarea);
    }

    function handleFileUpload(input) {
        if (input.files && input.files[0]) {
            document.getElementById('upload-text').classList.add('hidden');
            document.getElementById('upload-icon').innerText = 'check_circle';
            const nameEl = document.getElementById('file-name');
            nameEl.innerText = "Terpilih: " + input.files[0].name;
            nameEl.classList.remove('hidden');
        }
    }

    function closeModal() {
        document.getElementById('success-modal').classList.remove('show');
    }
</script>
@endpush
