<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa Kata Sandi | VolunteerHub</title>

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
                <h1 class="text-5xl font-extrabold leading-tight mb-6">Kami kirim OTP 6 digit ke email Anda.</h1>
                <p class="text-lg opacity-90 leading-relaxed font-light">Masukkan alamat email terdaftar untuk menerima kode verifikasi dan mengatur ulang kata sandi Anda.</p>
            </div>
            <div class="text-sm font-medium opacity-75">© 2026 VolunteerHub. Hak cipta dilindungi.</div>
        </div>
    </div>

    {{-- ===== PANEL KANAN (Form) ===== --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 md:p-16 bg-white">
        <div class="w-full max-w-md space-y-10">

            <div class="text-left">
                <h2 class="text-4xl font-extrabold text-[#263238] tracking-tight">Lupa Kata Sandi?</h2>
                <p class="mt-3 text-gray-500 text-lg">
                    {{ ($context ?? 'user') === 'organization' ? 'Portal organisasi' : 'Akun relawan' }}
                </p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                    <ul class="text-sm text-red-500 list-disc list-inside">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('forgot-password.send') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="context" value="{{ $context ?? 'user' }}"/>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-[#263238]" for="email">Alamat Email</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl group-focus-within:text-primary transition-colors">mail</span>
                        <input
                            required
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            @if(($context ?? 'user') === 'user') placeholder="nama@gmail.com" @endif
                            class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none placeholder:text-gray-400 @error('email') border-red-400 @enderror"
                        />
                    </div>
                    @error('email')<p class="text-red-600 text-xs font-semibold">{{ $message }}</p>@enderror
                    @if(($context ?? 'user') === 'user')
                        <p class="text-xs text-gray-400 mt-1">Hanya email @gmail.com.</p>
                    @endif
                </div>

                <button type="submit"
                        class="w-full bg-primary hover:bg-[#256661] text-white font-bold py-4 rounded-xl shadow-lg transition-all active:scale-[0.98] text-lg">
                    Kirim Kode OTP
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 font-medium">
                <a href="{{ ($context ?? 'user') === 'organization' ? route('admin.login') : route('login') }}"
                   class="text-primary font-bold hover:opacity-80 hover:underline transition-colors">
                    Kembali ke laman masuk
                </a>
            </p>

        </div>
    </div>
</div>

</body>
</html>
