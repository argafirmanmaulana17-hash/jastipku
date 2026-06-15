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
        $request->merge([
            'whatsapp' => $this->normalizeWhatsapp($request->whatsapp),
        ]);

        $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'whatsapp' => 'required|string|max:20|unique:users,whatsapp',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,jastiper',
            'agree' => 'accepted',
        ];

        if ($request->role === 'jastiper') {
            $rules['area_layanan'] = 'required|string|max:255';
            $rules['kendaraan'] = 'required|in:motor,sepeda,jalan';
        }

        $validated = $request->validate($rules, [
            'whatsapp.unique' => 'Nomor WhatsApp ini sudah terdaftar.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'whatsapp' => $validated['whatsapp'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'area_layanan' => $validated['area_layanan'] ?? null,
            'kendaraan' => $validated['kendaraan'] ?? null,
            'status' => 'offline',
        ]);

        Auth::login($user);

        return redirect()->route('whatsapp.verify')
            ->with('success', 'Akun berhasil dibuat! Silakan verifikasi WhatsApp kamu.');
    }

    private function normalizeWhatsapp(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            return '62'.substr($phone, 1);
        }

        if (! str_starts_with($phone, '62')) {
            return '62'.$phone;
        }

        return $phone;
    }
}
