<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Lupa Kata Sandi | VolunteerHub</title>
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

    {{-- Panel Kiri --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
        <img alt="Volunteers" class="absolute inset-0 w-full h-full object-cover scale-105"
             src="https://images.unsplash.com/photo-1559027615-cd762186c6cb?q=80&w=2074&auto=format&fit=crop"/>
        <div class="absolute inset-0 overlay-gradient flex flex-col justify-between p-16 text-white z-10">
            <div class="flex items-center gap-3">
                <img src="{{ config('volunteerhub.logo_url') }}" alt="VolunteerHub" class="h-10 w-10 object-contain rounded-lg bg-white/20 p-1"/>
                <span class="text-2xl font-bold tracking-tight">VolunteerHub</span>
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto object-contain"/>
                <span class="text-2xl font-bold">VolunteerHub</span>
            </div>
            <div class="max-w-md space-y-4">
                <h1 class="text-5xl font-extrabold leading-tight">Bawa perubahan dalam komunitas Anda hari ini.</h1>
                <p class="text-lg opacity-90 font-light">Bergabunglah dengan ribuan relawan yang membuat dunia lebih baik.</p>
            </div>
            <div class="text-sm font-medium opacity-75">© {{ date('Y') }} VolunteerHub.</div>
        </div>
    </div>

    {{-- Panel Kanan --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-4 md:p-10 bg-slate-50 relative dot-pattern min-h-screen">
        <div class="absolute top-10 right-10 w-64 h-64 bg-indigo-100 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-200/80 p-8 md:p-10 z-10 relative">

            <div class="lg:hidden flex items-center gap-2 mb-4 text-[#2F7F79]">
                <div class="w-8 h-8 rounded bg-[#2F7F79] text-white flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">volunteer_activism</span>
                </div>
                <span class="font-extrabold text-xl">VolunteerHub</span>
            </div>

            <h2 class="text-3xl md:text-4xl font-extrabold text-[#263238] tracking-tight">Lupa Kata Sandi?</h2>
            <p class="mt-2 text-gray-500 text-sm md:text-base">Masukkan email Anda dan kami akan mengirim link reset.</p>

            @if(session('status'))
                <div class="mt-4 bg-green-50 border border-green-200 rounded-xl p-4">
                    <p class="text-green-700 font-bold text-sm">{{ session('status') }}</p>
                </div>
            @endif

            <form class="space-y-5 mt-8" method="POST" action="{{ url('/forgot-password') }}">
                @csrf
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700">Alamat Email</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl">mail</span>
                        <input required name="email" type="email" value="{{ old('email') }}"
                               placeholder="nama@email.com"
                               class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-[#2F7F79] focus:border-transparent outline-none text-sm @error('email') border-red-400 @enderror"/>
                    </div>
                    @error('email')
                        <p class="text-xs text-red-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                        class="w-full bg-[#2F7F79] hover:bg-[#256661] text-white font-bold py-4 rounded-xl shadow-lg transition-all active:scale-[0.98] text-base">
                    Kirim Link Reset
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 font-medium mt-6">
                <a href="{{ route('login') }}" class="text-[#2F7F79] font-bold hover:underline flex items-center justify-center gap-1">
                    <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali ke Login
                </a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
