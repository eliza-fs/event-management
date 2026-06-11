<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VolunteerHub | Atur Ulang Kata Sandi</title>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700;800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{primary:'#2F7F79'}}}}</script>
</head>
<body class="bg-[#f5f7fa] font-['Lexend'] min-h-screen flex">
    <div class="hidden lg:flex lg:w-1/2 bg-[#2f7f79] text-white p-12 flex-col justify-between">
        <div class="flex items-center gap-3">
            <img src="{{ config('volunteerhub.logo_url') }}" alt="Logo" class="h-10 w-10 object-contain rounded-lg"/>
            <span class="text-2xl font-bold">VolunteerHub</span>
        </div>
        <p class="text-3xl font-extrabold leading-tight">Masukkan OTP dan password baru Anda.</p>
        <p class="text-sm opacity-80">© 2026 VolunteerHub</p>
    </div>
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6">
        <div class="w-full max-w-md bg-white rounded-3xl shadow-xl border p-8">
            <div class="flex items-center gap-3 mb-6 lg:hidden">
                <img src="{{ config('volunteerhub.logo_url') }}" alt="Logo" class="h-9 w-9 object-contain"/>
                <span class="font-bold text-primary">VolunteerHub</span>
            </div>
            <h2 class="text-2xl font-extrabold mb-1">Atur Ulang Password</h2>
            <p class="text-sm text-gray-500 mb-6">Email: <strong>{{ $email }}</strong></p>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 text-green-700 text-sm rounded-xl">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('password.reset.post') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}"/>
                <input type="hidden" name="context" value="{{ $context }}"/>

                <div>
                    <label class="text-sm font-bold">Kode OTP (6 digit)</label>
                    <input type="text" name="otp" value="{{ old('otp') }}" maxlength="6" pattern="\d{6}" required
                           class="w-full mt-1 rounded-xl border px-4 py-3 tracking-widest font-bold text-center focus:ring-2 focus:ring-primary outline-none @error('otp') border-red-500 @enderror"/>
                    @error('otp')<p class="text-red-600 text-xs font-semibold mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm font-bold">Password Baru</label>
                    <input type="password" name="password" required minlength="8" maxlength="12"
                           class="w-full mt-1 rounded-xl border px-4 py-3 focus:ring-2 focus:ring-primary outline-none @error('password') border-red-500 @enderror"/>
                    @error('password')<p class="text-red-600 text-xs font-semibold mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-gray-400 mt-1">8–12 karakter, huruf besar, angka, dan simbol.</p>
                </div>

                <div>
                    <label class="text-sm font-bold">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required minlength="8" maxlength="12"
                           class="w-full mt-1 rounded-xl border px-4 py-3 focus:ring-2 focus:ring-primary outline-none"/>
                    @error('password_confirmation')<p class="text-red-600 text-xs font-semibold mt-1">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:opacity-90">
                    Simpan Password Baru
                </button>
            </form>

            <a href="{{ route('forgot-password', ['context' => $context]) }}" class="block text-center text-sm text-primary font-bold mt-6">Minta OTP baru</a>
        </div>
    </div>
</body>
</html>
