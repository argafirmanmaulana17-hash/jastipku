<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $rules = [
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:users,email',
            'whatsapp'              => 'required|string|max:20',
            'password'              => 'required|string|min:8|confirmed',
            'role'                  => 'required|in:user,jastiper',
            'agree'                 => 'accepted',
        ];

        // Tambahan validasi kalau daftar sebagai jastiper
        if ($request->role === 'jastiper') {
            $rules['area_layanan'] = 'required|string|max:255';
            $rules['kendaraan']    = 'required|in:motor,sepeda,jalan';
        }

        $validated = $request->validate($rules);

        $user = User::create([
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'whatsapp'     => $validated['whatsapp'],
            'password'     => Hash::make($validated['password']),
            'role'         => $validated['role'],
            'area_layanan' => $validated['area_layanan'] ?? null,
            'kendaraan'    => $validated['kendaraan'] ?? null,
            'status'       => 'offline', 
        ]);

        Auth::login($user);

        return match($user->role) {
            'jastiper' => redirect('/dashboard/jastiper')->with('success', 'Akun jastiper berhasil dibuat! Aktifkan status kamu untuk mulai menerima order.'),
            default    => redirect('/')->with('success', 'Akun berhasil dibuat! Selamat datang di JastipKu.'),
        };
    }
}
