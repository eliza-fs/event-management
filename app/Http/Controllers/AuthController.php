<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\User;
use App\Rules\PasswordPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) {
            return auth()->user()->role === 'organization'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('home');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], $this->validationMessages());

        $user = User::where('email', $request->email)->whereNull('deleted_at')->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['password' => 'Password tidak benar']);
        }

        if ($user->role === 'organization') {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Gunakan portal admin untuk akun organisasi.']);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('home');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password' => ['required', 'confirmed', new PasswordPolicy],
        ], $this->validationMessages());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect()->route('home')
            ->with('success', 'Akun berhasil dibuat!');
    }

    public function logout(Request $request)
    {
        $wasOrganization = auth()->user()?->role === 'organization';

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect($wasOrganization ? route('admin.login') : route('login'));
    }

    public function showAdminLogin()
    {
        if (auth()->check()) {
            if (auth()->user()->role === 'organization') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home')
                ->with('error', 'Silakan logout terlebih dahulu untuk masuk sebagai organisasi.');
        }

        return view('auth.login_admin');
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], $this->validationMessages());

        $user = User::where('email', $request->email)
            ->whereNull('deleted_at')
            ->where('role', 'organization')
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['password' => 'Password tidak benar']);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function showAdminRegister()
    {
        $organizationCategories = OrganizationCategory::orderBy('name')->get();

        return view('auth.register_admin', compact('organizationCategories'));
    }

    public function adminRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password' => ['required', 'confirmed', new PasswordPolicy],
            'org_name' => 'required|string|max:255',
            'organization_category_id' => 'required|exists:organization_categories,id',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ], $this->validationMessages());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'organization',
        ]);

        Organization::create([
            'user_id' => $user->id,
            'organization_category_id' => $request->organization_category_id,
            'org_name' => $request->org_name,
            'description' => $request->description,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        Auth::login($user);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Akun organisasi berhasil dibuat!');
    }

    private function validationMessages(): array
    {
        return [
            'required' => 'Required.',
            'email.required' => 'Required.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Required.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'name.required' => 'Required.',
            'org_name.required' => 'Required.',
        ];
    }
}
