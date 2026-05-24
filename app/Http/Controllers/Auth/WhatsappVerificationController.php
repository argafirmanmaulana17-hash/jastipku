<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\WhatsappOtp;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WhatsappVerificationController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if ($user->whatsapp_verified_at) {
            return redirect('/dashboard');
        }

        return view('auth.verify-whatsapp');
    }

    public function sendOtp()
    {
        $user = Auth::user();

        if ($user->whatsapp_verified_at) {
            return redirect('/dashboard');
        }

        $whatsapp = $user->whatsapp ?? $user->no_hp ?? null;

        if (! $whatsapp) {
            return back()->withErrors([
                'whatsapp' => 'Nomor WhatsApp belum tersedia di akun kamu.',
            ]);
        }

        $otp = random_int(100000, 999999);

        WhatsappOtp::where('user_id', $user->id)
            ->whereNull('verified_at')
            ->delete();

        WhatsappOtp::create([
            'user_id' => $user->id,
            'whatsapp' => $whatsapp,
            'otp_hash' => Hash::make($otp),
            'expired_at' => now()->addMinutes(5),
            'attempts' => 0,
        ]);

        $sent = app(FonnteService::class)->sendOtp($whatsapp, (string) $otp);

        if ($sent) {
            return back()->with('success', 'Kode OTP sudah dikirim ke WhatsApp kamu.');
        }

        return back()
            ->with('error', 'mengirim OTP..')
            ->with('dev_otp', $otp);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = Auth::user();

        if ($user->whatsapp_verified_at) {
            return redirect('/dashboard');
        }

        $otpRecord = WhatsappOtp::where('user_id', $user->id)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (! $otpRecord) {
            return back()->withErrors([
                'otp' => 'Kode OTP belum dibuat. Klik kirim OTP terlebih dahulu.',
            ]);
        }

        if (now()->greaterThan($otpRecord->expired_at)) {
            return back()->withErrors([
                'otp' => 'Kode OTP sudah expired. Kirim ulang OTP.',
            ]);
        }

        if ($otpRecord->attempts >= 5) {
            return back()->withErrors([
                'otp' => 'Terlalu banyak percobaan. Kirim ulang OTP.',
            ]);
        }

        if (! Hash::check($request->otp, $otpRecord->otp_hash)) {
            $otpRecord->increment('attempts');

            return back()->withErrors([
                'otp' => 'Kode OTP salah.',
            ]);
        }

        $otpRecord->update([
            'verified_at' => now(),
        ]);

        User::where('id', $user->id)->update([
            'whatsapp_verified_at' => now(),
        ]);

        return redirect('/dashboard')->with('success', 'Nomor WhatsApp berhasil diverifikasi.');
    }
}
