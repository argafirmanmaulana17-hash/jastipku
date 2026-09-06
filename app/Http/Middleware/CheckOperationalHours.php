<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckOperationalHours
{
    public function handle(Request $request, Closure $next)
    {
        // 1. PRIORITAS MUTLAK: Selalu izinkan akses ke halaman Login, Register, & Auth lainnya.
        // Ini memastikan tombol "Login di sini" dari halaman tutup bisa diklik dan terbuka normal.
        if ($request->is('login*', 'register*', 'logout', 'password*')) {
            return $next($request);
        }

        // 2. PRIORITAS KEDUA: Jika yang akses adalah ADMIN (Sudah Login), bebas tembus!
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // 3. Ambil data pengaturan dari database
        $settings = Setting::pluck('value', 'key_name')->toArray();
        $isManualClose = $settings['is_manual_close'] ?? '0';
        $openTime = $settings['open_time'] ?? '06:00';
        $closeTime = $settings['close_time'] ?? '22:00';

        // 4. Deteksi Waktu Saat Ini (Zona Waktu WIB)
        $now = Carbon::now('Asia/Jakarta')->format('H:i');
        $isClosed = false;
        $reason = '';

        // Cek Saklar Manual
        if ($isManualClose === '1') {
            $isClosed = true;
            $reason = 'Website sedang ditutup secara manual untuk pemeliharaan (Maintenance).';
        } else {
            // Cek Jam Operasional
            if ($closeTime > $openTime) {
                if ($now >= $closeTime || $now < $openTime) {
                    $isClosed = true;
                }
            } else {
                if ($now >= $closeTime && $now < $openTime) {
                    $isClosed = true;
                }
            }
            $reason = "Layanan JastipKu saat ini sedang tutup. Jam operasional kami adalah $openTime - $closeTime WIB.";
        }

        // 5. Jika Tutup dan bukan Admin, tampilkan halaman istirahat
        if ($isClosed) {
            return response()->view('errors.closed', [
                'reason' => $reason,
                'openTime' => $openTime,
                'closeTime' => $closeTime,
            ]);
        }

        return $next($request);
    }
}
