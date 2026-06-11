<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Reset Kata Sandi | VolunteerHub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Lexend:wght@100..900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: 'Inter', 'Lexend', sans-serif; }
        .overlay-gradient { background: linear-gradient(135deg, rgba(47,127,121,0.9) 0%, rgba(25,118,210,0.9) 100%); }
        .dot-pattern { background-image: radial-gradient(#E2E8F0 1px, transparent 1px); background-size: 24px 24px; }
    </style>
</head>
<body class="bg-white text-[#263238] antialiased">
<div class="flex min-h-screen overflow-x-hidden">

    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
        <img alt="Volunteers" class="absolute inset-0 w-full h-full object-cover scale-105"
             src="https://images.unsplash.com/photo-1559027615-cd762186c6cb?q=80&w=2074&auto=format&fit=crop"/>
        <div class="absolute inset-0 overlay-gradient flex flex-col justify-between p-16 text-white z-10">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto object-contain"/>
                <span class="text-2xl font-bold">VolunteerHub</span>
            </div>
            <div class="max-w-md">
                <h1 class="text-5xl font-extrabold leading-tight">Bawa perubahan dalam komunitas Anda hari ini.</h1>
            </div>
            <div class="text-sm font-medium opacity-75">© {{ date('Y') }} VolunteerHub.</div>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-4 md:p-10 bg-slate-50 relative dot-pattern min-h-screen">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-200/80 p-8 md:p-10 z-10 relative">

            <div id="reset-form-container">
                <h2 class="text-3xl md:text-4xl font-extrabold text-[#263238] tracking-tight">Atur Ulang Kata Sandi</h2>
                <p class="mt-2 text-gray-500 text-sm">Masukkan kata sandi baru untuk akun Anda.</p>

                <form class="space-y-5 mt-8" id="resetPasswordForm" method="POST" action="{{ url('/reset-password') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token ?? '' }}"/>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Kata Sandi Baru</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl">lock</span>
                            <input required id="new-password" name="password" type="password" minlength="8" maxlength="15"
                                   placeholder="••••••••"
                                   class="w-full pl-12 pr-12 py-3.5 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-[#2F7F79] outline-none text-sm"/>
                            <button type="button" id="toggle-password"
                                    class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg hover:text-gray-600">
                                visibility
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Konfirmasi Kata Sandi</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl">lock_reset</span>
                            <input required id="confirm-password" name="password_confirmation" type="password" minlength="8" maxlength="15"
                                   placeholder="••••••••"
                                   class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-[#2F7F79] outline-none text-sm"/>
                        </div>
                        <p id="reset-hint" class="text-[11px] text-gray-400">Sandi 8-15 karakter, ada huruf besar, kecil, angka & simbol.</p>
                    </div>

                    <button type="submit" id="btn-submit"
                            class="w-full bg-[#2F7F79] hover:bg-[#256661] text-white font-bold py-4 rounded-xl shadow-lg transition-all active:scale-[0.98] text-base">
                        Simpan Kata Sandi
                    </button>
                </form>
            </div>

            <div id="reset-success-container" class="hidden text-center space-y-6 py-4">
                <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto">
                    <span class="material-symbols-outlined text-4xl">verified</span>
                </div>
                <h3 class="text-2xl font-bold text-[#263238]">Kata Sandi Berhasil Diperbarui!</h3>
                <p class="text-sm text-gray-500">Sekarang Anda bisa login dengan kata sandi baru.</p>
                <a href="{{ route('login') }}"
                   class="w-full block bg-[#2F7F79] hover:bg-[#256661] text-white font-bold py-3.5 rounded-xl transition-all text-center">
                    Kembali ke Login
                </a>
            </div>

            <div class="pt-4 text-center border-t border-gray-100 mt-6">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-gray-500 hover:text-[#2F7F79] transition-colors">
                    <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</div>
<script>
    const toggle = document.getElementById('toggle-password');
    const pass = document.getElementById('new-password');
    const confirm = document.getElementById('confirm-password');
    if(toggle) toggle.addEventListener('click', () => {
        const isPass = pass.type === 'password';
        pass.type = isPass ? 'text' : 'password';
        confirm.type = isPass ? 'text' : 'password';
        toggle.textContent = isPass ? 'visibility_off' : 'visibility';
    });
    document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
        const pw = pass.value;
        const cpw = confirm.value;
        const hint = document.getElementById('reset-hint');
        if (pw !== cpw) {
            e.preventDefault();
            hint.textContent = '❌ Kata sandi tidak cocok!';
            hint.className = 'text-[11px] text-red-500 font-bold';
            return;
        }
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,15}$/;
        if (!regex.test(pw)) {
            e.preventDefault();
            hint.textContent = '❌ Sandi harus 8-15 karakter, ada huruf besar, kecil, angka & simbol.';
            hint.className = 'text-[11px] text-red-500 font-bold';
        }
    });
</script>
</body>
</html>
