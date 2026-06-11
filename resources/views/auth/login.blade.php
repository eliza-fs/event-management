<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Masuk | VolunteerHub</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <style>
        body { font-family: 'Lexend', sans-serif; }
        .overlay-gradient {
            background: linear-gradient(135deg, rgba(47,127,121,0.85) 0%, rgba(25,118,210,0.85) 100%);
        }
    </style>
</head>
<body class="bg-white text-[#263238] overflow-x-hidden">

<div class="flex min-h-screen">

    {{-- ===== PANEL KIRI (Foto + Teks) ===== --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
        <img alt="Volunteers"
             class="absolute inset-0 w-full h-full object-cover scale-105"
             src="https://images.unsplash.com/photo-1559027615-cd762186c6cb?q=80&w=2074&auto=format&fit=crop"/>
        <div class="absolute inset-0 overlay-gradient flex flex-col justify-between p-16 text-white">
            <div class="flex items-center gap-3">
                <img src="{{ config('volunteerhub.logo_url') }}" alt="VolunteerHub" class="h-10 w-10 object-contain rounded-lg bg-white/20 p-1"/>
                <span class="text-2xl font-bold tracking-tight">VolunteerHub</span>
            </div>
            <div class="max-w-md">
                <h1 class="text-5xl font-extrabold leading-tight mb-6">Berikan dampak positif di komunitasmu hari ini.</h1>
                <p class="text-lg opacity-90 leading-relaxed font-light">Bergabunglah dengan ribuan relawan di seluruh dunia yang membuat dunia menjadi tempat yang lebih baik.</p>
            </div>
            <div class="text-sm font-medium opacity-75">© 2026 VolunteerHub. Hak cipta dilindungi.</div>
        </div>
    </div>

    {{-- ===== PANEL KANAN (Form Login) ===== --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 md:p-16 bg-white">
        <div class="w-full max-w-md space-y-10">

            <div class="text-left">
                <h2 class="text-4xl font-extrabold text-[#263238] tracking-tight">Selamat datang kembali</h2>
                <p class="mt-3 text-gray-500 text-lg">Masukkan detail Anda untuk mengelola kontribusi Anda.</p>
            </div>

            {{-- Tampilkan error dari Laravel jika ada --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                    <p class="text-sm font-bold text-red-600">Gagal masuk:</p>
                    <ul class="mt-1 text-sm text-red-500 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Pesan sukses (misal: setelah reset password) --}}
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                    <p class="text-sm font-bold text-green-600">{{ session('success') }}</p>
                </div>
            @endif

            {{-- ===== FORM LOGIN ===== --}}
            {{-- TODO Backend: action akan POST ke route login Laravel --}}
            <form class="space-y-6" action="{{ route('login.post') }}" method="POST" id="loginForm">
                @csrf

                {{-- Email --}}
                <div class="space-y-2">
                    <label class="text-sm font-bold text-[#263238]" for="email">Alamat email</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl group-focus-within:text-primary transition-colors">mail</span>
                        <input
                            required
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none placeholder:text-gray-400 @error('email') border-red-400 @enderror"
                        />
                    </div>
                    @error('email')
                        <p class="text-red-600 text-xs font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-bold text-[#263238]" for="password">Kata sandi</label>
                        <a class="text-sm font-bold text-primary hover:opacity-80 hover:underline transition-colors"
                           href="{{ route('forgot-password') }}">Lupa kata sandi?</a>
                    </div>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl group-focus-within:text-primary transition-colors">lock</span>
                        <input
                            required
                            id="password"
                            name="password"
                            type="password"
                            minlength="8"
                            maxlength="12"
                            placeholder="••••••••"
                            class="w-full pl-12 pr-12 py-3.5 rounded-xl border @error('password') border-red-500 @else border-gray-200 @enderror bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none placeholder:text-gray-400"
                        />
                        @error('password')
                        <p class="text-red-600 text-xs font-semibold mt-1">{{ $message }}</p>
                        @enderror
                        <button id="togglePassword" type="button"
                                class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl hover:text-gray-600 transition-colors">
                            visibility
                        </button>
                    </div>
                    <p id="pass-hint" class="text-xs text-gray-400 mt-1">
                        8-15 karakter, harus ada huruf besar, kecil, angka, dan simbol.
                    </p>
                </div>

                {{-- Tombol Submit --}}
                <button type="submit"
                        class="w-full bg-primary hover:bg-[#256661] text-white font-bold py-4 rounded-xl shadow-lg transition-all active:scale-[0.98] text-lg">
                    Masuk
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 font-medium">
                Belum punya akun?
                <a class="text-primary font-bold hover:opacity-80 hover:underline transition-colors"
                   href="{{ url('/register') }}">Buat akun</a>
            </p>

        </div>
    </div>
</div>

<script>
    // Show / Hide Password
    const togglePassword = document.querySelector('#togglePassword');
    const passwordInput  = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.textContent = type === 'password' ? 'visibility' : 'visibility_off';
    });

    // Validasi kompleksitas password saat submit
    const loginForm = document.getElementById('loginForm');
    const passHint  = document.getElementById('pass-hint');

    loginForm.addEventListener('submit', function (e) {
        const password = passwordInput.value;
        const complexityRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,15}$/;

        if (!complexityRegex.test(password)) {
            e.preventDefault();
            passHint.classList.remove('text-gray-400');
            passHint.classList.add('text-red-500', 'font-bold');
            passHint.textContent = '❌ Kata sandi belum memenuhi syarat!';
        }
    });
</script>

</body>
</html>
