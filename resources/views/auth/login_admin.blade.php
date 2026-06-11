<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'VolunteerHub') }} | Admin Login</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Lexend:wght@100..900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: '#2F7F79',
              'primary-hover': '#256661',
              'secondary-blue': '#1976D2',
              'background-light': '#f5f7fa',
              'soft-black': '#263238'
            },
            fontFamily: {
              sans: ['Inter', 'Lexend', 'ui-sans-serif', 'system-ui', 'sans-serif'],
              display: ['Inter', 'Lexend', 'sans-serif']
            }
          }
        }
      }
    </script>
    
    <style>
      body {
        font-family: 'Inter', 'Lexend', ui-sans-serif, system-ui, sans-serif;
        background-color: #f5f7fa;
        color: #263238;
      }
      .overlay-gradient {
        background: linear-gradient(135deg, rgba(47, 127, 121, 0.9) 0%, rgba(25, 118, 210, 0.9) 100%);
      }
      .dot-pattern {
        background-image: radial-gradient(#E2E8F0 1px, transparent 1px);
        background-size: 24px 24px;
      }
    </style>
  </head>
  <body class="bg-white text-soft-black antialiased font-sans">
    <div class="flex min-h-screen bg-white text-soft-black overflow-x-hidden">
      
      <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
        <img 
          alt="Admin dashboard setup" 
          class="absolute inset-0 w-full h-full object-cover transform scale-105" 
          src="https://images.unsplash.com/photo-1559027615-cd762186c6cb?q=80&w=2074&auto=format&fit=crop"
        />
        <div class="absolute inset-0 overlay-gradient flex flex-col justify-between p-12 xl:p-16 text-white z-10">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 flex items-center justify-center overflow-hidden rounded-lg">
                    <img src="{{ asset('images/logo.png') }}" alt="VolunteerHub Logo" class="size-20 object-contain" onerror="this.style.display='none'" />
            </div>
            <span class="text-2xl font-bold tracking-tight text-white font-display">VolunteerHub Admin</span>
          </div>

          <div class="max-w-md space-y-4">
            <h1 class="text-4xl xl:text-5xl font-extrabold leading-tight font-display text-white">
              Kelola sistem dengan efisien dan aman.
            </h1>
            <p class="text-base xl:text-lg opacity-90 leading-relaxed font-light font-display">
              Masuk untuk memantau aktivitas, pengguna, dan operasional platform.
            </p>
          </div>

          <div class="text-sm font-medium opacity-75 font-display">
            © {{ date('Y') }} VolunteerHub. Hak Cipta Dilindungi.
          </div>
        </div>
      </div>

      <div class="w-full lg:w-1/2 flex items-center justify-center p-4 sm:p-8 md:p-12 bg-slate-50 relative dot-pattern min-h-screen overflow-y-auto">
        <div class="absolute top-10 right-10 w-48 h-48 sm:w-64 sm:h-64 bg-indigo-100 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
        <div class="absolute bottom-10 left-10 w-56 h-56 sm:w-72 sm:h-72 bg-blue-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl shadow-slate-200/70 border border-slate-200/80 p-6 sm:p-8 md:p-10 z-10 relative my-auto">
          <div class="space-y-8">
            <div class="text-left">
              <div class="flex items-center gap-2 mb-6 lg:hidden text-primary">
                <span class="font-extrabold tracking-tight text-xl font-display">VolunteerHub Admin</span>
              </div>
              <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#263238] tracking-tight font-display">Portal Administrator</h2>
              <p class="mt-2 text-gray-500 text-sm sm:text-base font-display">Masukkan kredensial admin Anda untuk mengakses dashboard.</p>
            </div>

            <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5" id="loginForm">
              @csrf

              <div class="space-y-1.5">
                <label class="text-xs sm:text-sm font-bold text-slate-700 font-display" for="email">Alamat Email</label>
                <div class="relative group">
                  <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl transition-colors">mail</span>
                  <input required id="email" name="email" type="email" value="{{ old('email') }}" class="w-full pl-12 pr-4 py-3 rounded-xl border @error('email') border-red-500 @else border-gray-200 @enderror bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none placeholder:text-gray-400 text-soft-black text-sm sm:text-base" placeholder="admin@volunteerhub.com" autofocus />
                </div>
                @error('email')
                  <p class="text-xs text-red-500 mt-1 font-medium font-display">{{ $message }}</p>
                @enderror
              </div>

              <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                  <label class="text-xs sm:text-sm font-bold text-slate-700 font-display" for="password">Kata Sandi</label>
                </div>
                <div class="relative group">
                  <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl transition-colors">lock</span>
                  <input required id="password" name="password" type="password" minlength="8" maxlength="12" class="w-full pl-12 pr-12 py-3 rounded-xl border @error('password') border-red-500 @else border-gray-200 @enderror bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none placeholder:text-gray-400 text-soft-black text-sm sm:text-base" placeholder="••••••••" />
                  <button id="toggle-password" class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl hover:text-gray-600 transition-colors" type="button">visibility</button>
                </div>
                @error('password')
                  <p class="text-red-600 text-xs font-semibold mt-1 font-display">{{ $message }}</p>
                @enderror
              </div>

              <div class="flex items-center justify-between py-1">
                <div class="flex items-center">
                  <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded cursor-pointer accent-primary" />
                  <label class="ml-2 block text-xs sm:text-sm text-gray-600 font-medium cursor-pointer select-none" for="remember">Ingat sesi admin saya</label>
                </div>
                <a href="{{ route('admin.forgot-password') }}" class="text-xs font-bold text-primary hover:underline">Lupa kata sandi?</a>
              </div>

              @if(session('success'))
                <div class="p-3 bg-green-50 text-green-700 text-sm rounded-xl font-medium">{{ session('success') }}</div>
              @endif

              <button class="w-full bg-primary hover:bg-[#256661] text-white font-bold py-3.5 rounded-xl shadow-lg shadow-primary/20 transition-all active:scale-[0.99] text-base sm:text-lg cursor-pointer mt-2" type="submit">
                Masuk Dashboard
              </button>
            </form>

            @if (Route::has('admin.register'))
              <p class="text-center text-xs sm:text-sm text-gray-500 font-medium pt-2">
                Belum memiliki akses admin?
                <a href="{{ route('admin.register') }}" class="text-primary font-bold hover:opacity-80 hover:underline transition-colors">
                  Daftar di sini
                </a>
              </p>
            @endif
          </div>
        </div>
      </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const passwordInput = document.getElementById('password');
        const togglePasswordBtn = document.getElementById('toggle-password');

        if (togglePasswordBtn && passwordInput) {
          togglePasswordBtn.addEventListener('click', () => {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            togglePasswordBtn.textContent = isPassword ? 'visibility_off' : 'visibility';
          });
        }
      });
    </script>
  </body>
</html>
