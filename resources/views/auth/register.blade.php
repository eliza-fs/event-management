<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Daftar | VolunteerHub</title>

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

    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
        <img alt="Volunteers"
             class="absolute inset-0 w-full h-full object-cover scale-105"
             src="https://images.unsplash.com/photo-1559027615-cd762186c6cb?q=80&w=2074&auto=format&fit=crop"/>
        <div class="absolute inset-0 overlay-gradient flex flex-col justify-between p-16 text-white">
            <div class="flex items-center gap-3">
                <div class="size-10 bg-white rounded-lg flex items-center justify-center text-primary shadow-lg">
                    <svg class="size-8" fill="currentColor" viewBox="0 0 48 48">
                        <path d="M13.8261 30.5736C16.7203 29.8826 20.2244 29.4783 24 29.4783C27.7756 29.4783 31.2797 29.8826 34.1739 30.5736C36.9144 31.2278 39.9967 32.7669 41.3563 33.8352L24.8486 7.36089C24.4571 6.73303 23.5429 6.73303 23.1514 7.36089L6.64374 33.8352C8.00331 32.7669 11.0856 31.2278 13.8261 30.5736Z"></path>
                    </svg>
                </div>
                <span class="text-2xl font-bold tracking-tight">VolunteerHub</span>
            </div>
            <div class="max-w-md">
                <h1 class="text-5xl font-extrabold leading-tight mb-6">Berikan dampak positif di komunitasmu hari ini.</h1>
                <p class="text-lg opacity-90 leading-relaxed font-light">Bergabunglah dengan ribuan relawan di seluruh dunia yang membuat dunia menjadi tempat yang lebih baik.</p>
            </div>
            <div class="text-sm font-medium opacity-75">© 2026 VolunteerHub. Hak cipta dilindungi.</div>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 md:p-16 bg-white">
        <div class="w-full max-w-md space-y-10">

            <div class="text-left">
                <h2 class="text-4xl font-extrabold text-[#263238] tracking-tight">Buat akun</h2>
                <p class="mt-3 text-gray-500 text-lg">Mulai perjalanan relawanmu bersama kami.</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                    <ul class="mt-1 text-sm text-red-500 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="space-y-6" action="{{ route('register.post') }}" method="POST" id="registerForm">
                @csrf

                <div class="space-y-2">
                    <label class="text-sm font-bold text-[#263238]" for="name">Nama lengkap</label>
                    <input required id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Nama Anda"
                           class="w-full px-4 py-3.5 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-primary outline-none @error('name') border-red-500 @enderror"/>
                    @error('name')<p class="text-red-600 text-xs font-semibold">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-[#263238]" for="email">Alamat email</label>
                    <input required id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@email.com"
                           class="w-full px-4 py-3.5 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-primary outline-none @error('email') border-red-500 @enderror"/>
                    @error('email')<p class="text-red-600 text-xs font-semibold">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-[#263238]" for="password">Kata sandi</label>
                    <input required id="password" name="password" type="password" minlength="8" maxlength="12" placeholder="••••••••"
                           class="w-full px-4 py-3.5 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-primary outline-none @error('password') border-red-500 @enderror"/>
                    @error('password')<p class="text-red-600 text-xs font-semibold">{{ $message }}</p>@enderror
                    <p class="text-xs text-gray-400">8–12 karakter, huruf besar, angka, dan simbol.</p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-[#263238]" for="password_confirmation">Konfirmasi kata sandi</label>
                    <input required id="password_confirmation" name="password_confirmation" type="password" minlength="8" maxlength="12" placeholder="••••••••"
                           class="w-full px-4 py-3.5 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-primary outline-none"/>
                </div>

                <button type="submit"
                        class="w-full bg-primary hover:bg-[#256661] text-white font-bold py-4 rounded-xl shadow-lg transition-all active:scale-[0.98] text-lg">
                    Buat akun
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 font-medium">
                Sudah punya akun?
                <a class="text-primary font-bold hover:opacity-80 hover:underline transition-colors"
                   href="{{ route('login') }}">Masuk</a>
            </p>

        </div>
    </div>
</div>

</body>
</html>
