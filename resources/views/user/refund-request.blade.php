@extends('layouts.app')
@section('title', 'Ajukan Refund | VolunteerHub')

@section('content')
<main class="flex-grow max-w-3xl mx-auto py-12 px-6 w-full">
    <div class="bg-white rounded-3xl shadow-xl p-8 border">
        <h1 class="text-4xl font-black mb-2">Ajukan Refund</h1>
        <p class="text-gray-500 mb-8">Lengkapi form refund berikut.</p>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                <p class="text-green-700 font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <p class="text-red-700 font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <ul class="text-red-700 text-sm list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('refund.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block font-semibold mb-2">Pembayaran (Kegiatan)</label>
                <select name="payment_id" required
                        class="w-full border rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-[#2F7F79]">
                    <option value="">Pilih pembayaran yang sudah diverifikasi</option>
                    @foreach($eligiblePayments as $payment)
                        <option value="{{ $payment->id }}" @selected(old('payment_id') == $payment->id)>
                            {{ $payment->payment_code }} — {{ $payment->eventRegistration->event->title ?? 'Event' }}
                            (Rp{{ number_format($payment->amount, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
                @if($eligiblePayments->isEmpty())
                    <p class="text-xs text-amber-600 mt-2">Tidak ada pembayaran terverifikasi yang dapat diajukan refund.</p>
                @endif
            </div>
            <div>
                <label class="block font-semibold mb-2">Alasan Refund</label>
                <select name="reason" required class="w-full border rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-[#2F7F79]">
                    <option value="">Pilih alasan</option>
                    <option value="Bentrok Jadwal" @selected(old('reason') === 'Bentrok Jadwal')>Bentrok Jadwal</option>
                    <option value="Keadaan Darurat" @selected(old('reason') === 'Keadaan Darurat')>Keadaan Darurat</option>
                    <option value="Pendaftaran Ganda" @selected(old('reason') === 'Pendaftaran Ganda')>Pendaftaran Ganda</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-2">Detail Tambahan</label>
                <textarea name="description" rows="4" placeholder="Jelaskan alasan refund..." required
                          class="w-full border rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-[#2F7F79]">{{ old('description') }}</textarea>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2 text-gray-700">Bank / E-Wallet</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}" placeholder="BCA / GoPay" required
                           class="w-full border rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-[#2F7F79]"/>
                </div>
                <div>
                    <label class="block mb-2 text-gray-700">Nomor Rekening</label>
                    <input type="text" name="account_number" value="{{ old('account_number') }}" placeholder="123456789" required
                           inputmode="numeric" pattern="\d+" maxlength="30"
                           class="w-full border rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-[#2F7F79]"/>
                    @error('account_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block mb-2 text-gray-700">Nama Pemilik Rekening</label>
                <input type="text" name="account_holder" value="{{ old('account_holder') }}" placeholder="Nama lengkap" required
                       class="w-full border rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-[#2F7F79]"/>
            </div>
            <div class="pt-4">
                <button type="submit" @disabled($eligiblePayments->isEmpty())
                        class="w-full bg-[#2F7F79] text-white font-bold py-3 rounded-xl hover:bg-[#256b66] transition disabled:opacity-50">
                    Kirim Refund
                </button>
            </div>
        </form>
    </div>
</main>
@endsection
