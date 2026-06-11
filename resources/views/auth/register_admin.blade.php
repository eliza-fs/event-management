<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>VolunteerHub | Daftar Akun Admin</title>
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
          alt="Server management" 
          class="absolute inset-0 w-full h-full object-cover transform scale-105" 
          src="https://images.unsplash.com/photo-1559027615-cd762186c6cb?q=80&w=2074&auto=format&fit=crop"
        />
        <div class="absolute inset-0 overlay-gradient flex flex-col justify-between p-16 text-white z-10">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 flex items-center justify-center overflow-hidden rounded-lg">
                    <img src="{{ asset('images/logo.png') }}" alt="VolunteerHub Logo" class="size-20 object-contain" onerror="this.style.display='none'" />
            </div>
            <span class="text-2xl font-bold tracking-tight text-white font-display">VolunteerHub Admin</span>
          </div>

          <div class="max-w-md space-y-4">
            <h1 class="text-5xl font-extrabold leading-tight font-display text-white">
              Kelola sistem dengan efisien dan aman.
            </h1>
            <p class="text-lg opacity-90 leading-relaxed font-light font-display">
              Masuk untuk memantau aktivitas, pengguna, dan operasional platform.
            </p>
          </div>

          <div class="text-sm font-medium opacity-75 font-display">
            © {{ date('Y') }} VolunteerHub. Hak Cipta Dilindungi.
          </div>
        </div>
      </div>

      <div class="w-full lg:w-1/2 flex items-center justify-center p-4 sm:p-8 md:p-12 bg-slate-50 relative dot-pattern min-h-screen overflow-y-auto">
        <div class="absolute top-10 right-10 w-48 h-48 md:w-64 md:h-64 bg-indigo-100 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
        <div class="absolute bottom-10 left-10 w-56 h-56 md:w-72 md:h-72 bg-blue-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl shadow-slate-200/70 border border-slate-200/80 p-6 sm:p-8 md:p-10 z-10 relative">
          <div class="space-y-6 md:space-y-8">
            <div class="text-left">
              <div class="flex items-center gap-2 mb-4 lg:hidden text-primary">
                <span class="font-extrabold tracking-tight text-xl font-display">VolunteerHub Admin</span>
              </div>
              <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#263238] tracking-tight font-display">Registrasi Administrator</h2>
              <p class="mt-2 text-gray-500 text-xs sm:text-sm md:text-base font-display">Buat akun admin untuk mendapatkan akses penuh ke dalam sistem.</p>
            </div>

            @if ($errors->any())
              <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-xs sm:text-sm font-medium">
                <ul class="list-disc list-inside">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <form class="space-y-4" id="registerForm" action="{{ route('admin.register.post') }}" method="POST">
              @csrf
              
              <div class="space-y-1">
                <label class="text-xs sm:text-sm font-bold font-display text-slate-700" for="fullName">Nama Lengkap</label>
                <div class="relative group">
                  <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl transition-colors">person</span>
                  <input required id="fullName" name="name" type="text" value="{{ old('name') }}" class="w-full pl-12 pr-4 py-2.5 sm:py-3 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none placeholder:text-gray-400 text-soft-black text-sm" placeholder="Admin Utama" />
                </div>
              </div>

              <div class="space-y-1">
                <label class="text-xs sm:text-sm font-bold font-display text-slate-700" for="reg-email">Alamat Email Resmi</label>
                <div class="relative group">
                  <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl transition-colors">mail</span>
                  <input required id="reg-email" name="email" type="email" value="{{ old('email') }}" class="w-full pl-12 pr-4 py-2.5 sm:py-3 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none placeholder:text-gray-400 text-soft-black text-sm" placeholder="admin@volunteerhub.com" />
                </div>
              </div>
              
              <div class="space-y-1">
                <label class="text-xs sm:text-sm font-bold font-display text-slate-700" for="reg-password">Kata Sandi</label>
                <div class="relative group">
                  <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl transition-colors">lock</span>
                  <input required id="reg-password" name="password" type="password" minlength="8" maxlength="12" class="w-full pl-12 pr-12 py-2.5 sm:py-3 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none placeholder:text-gray-400 text-soft-black text-sm @error('password') border-red-500 @enderror" placeholder="••••••••" />
                  @error('password')<p class="text-red-600 text-xs font-semibold">{{ $message }}</p>@enderror
                  <button id="toggle-password" class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg hover:text-gray-600 transition-colors" type="button">visibility</button>
                </div>
              </div>

              <div class="space-y-1">
                <label class="text-xs sm:text-sm font-bold font-display text-slate-700" for="confirm-password">Konfirmasi Kata Sandi</label>
                <div class="relative group">
                  <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl transition-colors">lock_reset</span>
                  <input required id="confirm-password" name="password_confirmation" type="password" minlength="8" maxlength="12" class="w-full pl-12 pr-4 py-2.5 sm:py-3 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none placeholder:text-gray-400 text-soft-black text-sm" placeholder="••••••••" />
                </div>
                <p id="reg-hint" class="text-[10px] sm:text-[11px] text-gray-400 leading-normal transition-colors pt-1">
                  8–12 karakter, huruf besar, angka, dan simbol.
                </p>
              </div>

              <div class="space-y-1">
                <label class="text-xs sm:text-sm font-bold font-display text-slate-700" for="org_name">Nama Organisasi</label>
                <input required id="org_name" name="org_name" type="text" value="{{ old('org_name') }}" class="w-full rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-primary p-3 text-sm" placeholder="Nama organisasi" />
              </div>

              <div class="space-y-1">
                <label class="text-xs sm:text-sm font-bold font-display text-slate-700" for="organization_category_id">Kategori Organisasi</label>
                <select required id="organization_category_id" name="organization_category_id" class="w-full rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-primary p-3 text-sm">
                  <option value="">Pilih kategori</option>
                  @foreach($organizationCategories ?? [] as $category)
                    <option value="{{ $category->id }}" @selected(old('organization_category_id') == $category->id)>{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="space-y-1">
                <label class="text-xs sm:text-sm font-bold font-display text-slate-700" for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="2" class="w-full rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-primary p-3 text-sm">{{ old('description') }}</textarea>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="space-y-1">
                  <label class="text-xs sm:text-sm font-bold font-display text-slate-700" for="phone">Telepon</label>
                  <input id="phone" name="phone" type="text" value="{{ old('phone') }}" class="w-full rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-primary p-3 text-sm" />
                </div>
                <div class="space-y-1">
                  <label class="text-xs sm:text-sm font-bold font-display text-slate-700" for="address">Alamat</label>
                  <input id="address" name="address" type="text" value="{{ old('address') }}" class="w-full rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-primary p-3 text-sm" />
                </div>
              </div>

              <div class="flex items-start pt-1">
                <input required id="agree-pledge" type="checkbox" class="mt-0.5 h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded cursor-pointer accent-primary shrink-0" />
                <label class="ml-2 block text-[11px] sm:text-xs text-slate-600 leading-normal cursor-pointer" for="agree-pledge">
                  Saya berjanji untuk menjaga kerahasiaan data pengguna dan mengelola sistem dengan penuh tanggung jawab.
                </label>
              </div>

              <button class="w-full bg-primary hover:bg-[#256661] text-white font-bold py-3 sm:py-3.5 rounded-xl shadow-lg shadow-primary/20 transition-all active:scale-[0.98] text-sm sm:text-base cursor-pointer mt-2" type="submit">
                Buat Akun Admin
              </button>
            </form>

            <p class="text-center text-xs sm:text-sm text-gray-500 font-medium font-sans">
              Sudah memiliki akun admin?
              <a href="{{ route('admin.login') }}" class="text-primary font-bold hover:opacity-80 hover:underline transition-colors">
                Masuk di sini
              </a>
            </p>
          </div>
        </div>
      </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const registerForm = document.getElementById('registerForm');
        const passwordInput = document.getElementById('reg-password');
        const confirmPasswordInput = document.getElementById('confirm-password');
        const agreePledgeCheckbox = document.getElementById('agree-pledge');
        const togglePasswordBtn = document.getElementById('toggle-password');
        const regHint = document.getElementById('reg-hint');

        if (togglePasswordBtn && passwordInput && confirmPasswordInput) {
          togglePasswordBtn.addEventListener('click', () => {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            confirmPasswordInput.setAttribute('type', isPassword ? 'text' : 'password');
            togglePasswordBtn.textContent = isPassword ? 'visibility_off' : 'visibility';
          });
        }

        if (registerForm && passwordInput && confirmPasswordInput && agreePledgeCheckbox) {
          registerForm.addEventListener('submit', (e) => {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            const agreePledge = agreePledgeCheckbox.checked;

            if (!agreePledge) {
              e.preventDefault();
              alert('Anda harus menyetujui persyaratan kerahasiaan data!');
              return;
            }

            if (password !== confirmPassword) {
              e.preventDefault();
              if (regHint) {
                regHint.textContent = 'Sandi tidak cocok! Silakan ketik kembali password dengan tepat.';
                regHint.className = 'text-[11px] text-red-500 font-bold leading-normal';
              }
              alert('Sandi dan Konfirmasi Sandi tidak cocok!');
              return;
            }

            const complexityRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,15}$/;
            if (!complexityRegex.test(password)) {
              e.preventDefault();
              if (regHint) {
                regHint.textContent = 'Kata sandi harus terdiri dari 8-15 karakter dan mengandung huruf besar, huruf kecil, angka, serta simbol.';
                regHint.className = 'text-[11px] text-red-500 font-bold leading-normal animate-pulse';
              }
              alert("Sandi belum memenuhi syarat!\n- Panjang 8-15 karakter\n- Harus ada huruf besar & kecil\n- Harus ada angka & simbol");
              return;
            }
          });
        }
      });
    </script>
  </body>
</html>
