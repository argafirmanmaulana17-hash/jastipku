<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'whatsapp' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,jastiper',
            'agree' => 'accepted',
        ];

        if ($request->role === 'jastiper') {
            $rules['area_layanan'] = 'required|string|max:255';
            $rules['kendaraan'] = 'required|in:motor,sepeda,jalan';
        }

        $validated = $request->validate($rules);

        $whatsapp = preg_replace('/[^0-9]/', '', $validated['whatsapp']);

        if (str_starts_with($whatsapp, '62')) {
            $whatsapp = '0'.substr($whatsapp, 2);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'whatsapp' => $whatsapp,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'area_layanan' => $validated['area_layanan'] ?? null,
            'kendaraan' => $validated['kendaraan'] ?? null,
            'status' => 'offline',
            'whatsapp_verified_at' => null,
        ]);

        Auth::login($user);

        return redirect()->route('whatsapp.verify')
            ->with('success', 'Akun berhasil dibuat. Silakan verifikasi nomor WhatsApp terlebih dahulu.');
    }
}
