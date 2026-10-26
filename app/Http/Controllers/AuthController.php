<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'role' => ['nullable', 'string', 'in:owner,admin,staff,customer,pelanggan'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password'],
        ];

        $remember = $request->boolean('remember');
        $selectedRole = $request->input('role');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Verifikasi kecocokan role pengguna jika dipilih dari form login
            if ($selectedRole) {
                $normalizedSelectedRole = match($selectedRole) {
                    'owner', 'admin' => 'admin',
                    'staff', 'admin_staff', 'staff_admin' => 'staff',
                    'customer', 'pelanggan' => 'customer',
                    default => $selectedRole,
                };

                $normalizedUserRole = match($user->role) {
                    'owner', 'admin' => 'admin',
                    'staff', 'admin_staff', 'staff_admin' => 'staff',
                    'customer', 'pelanggan' => 'customer',
                    default => $user->role,
                };

                if ($normalizedSelectedRole !== $normalizedUserRole) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    $roleLabels = [
                        'admin' => 'Owner',
                        'staff' => 'Staff/Admin',
                        'customer' => 'Pelanggan',
                    ];
                    $selectedLabel = $roleLabels[$normalizedSelectedRole] ?? ucfirst($selectedRole);
                    $actualLabel = $roleLabels[$normalizedUserRole] ?? ucfirst($user->role);

                    return back()->withErrors([
                        'email' => "Role yang Anda pilih ({$selectedLabel}) tidak sesuai dengan peran akun ini. Akun Anda terdaftar sebagai {$actualLabel}.",
                    ])->onlyInput('email', 'role');
                }
            }

            $request->session()->regenerate();
            $message = 'Selamat datang kembali, ' . $user->name . ' (' . ucfirst($user->role) . ')!';
            $intended = session()->get('url.intended');

            if ($user->isCustomer()) {
                // Jangan redirect ke intended jika URL tersebut adalah halaman admin atau staff
                if ($intended && !str_contains($intended, '/admin') && !str_contains($intended, '/staff') && !str_contains($intended, '/users') && !str_contains($intended, '/purchases') && !str_contains($intended, '/reports')) {
                    return redirect()->intended(route('customer.dashboard'))->with('success', $message);
                }
                return redirect()->route('customer.dashboard')->with('success', $message);
            } elseif ($user->isStaff()) {
                // Jangan redirect ke intended jika URL tersebut adalah halaman khusus admin atau pelanggan
                if ($intended && !str_contains($intended, '/admin') && !str_contains($intended, '/customer') && !str_contains($intended, '/checkout')) {
                    return redirect()->intended(route('staff.dashboard'))->with('success', $message);
                }
                return redirect()->route('staff.dashboard')->with('success', $message);
            }

            return redirect()->intended(route('admin.dashboard'))->with('success', $message);
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email', 'role');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer', // Self registration is customer by default
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('customer.dashboard')->with('success', 'Pendaftaran akun berhasil! Selamat datang di KOPI SENJA.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar (logout).');
    }
}
