<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Rating;

class DashboardController extends Controller
{
    // Redirect ke dashboard sesuai role
    public function redirect()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return redirect()->route('dashboard.admin.index');
            case 'jastiper':
                return redirect()->route('dashboard.jastiper.index');
            default:
                return redirect()->route('home');
        }
    }

    // ==========================================
    // ADMIN DASHBOARD
    // ==========================================

    public function admin()
    {
        $stats = [
            'total_orders' => Order::count(),
            'today_orders' => Order::today()->count(),
            'active_jastipers' => User::activeJastipers()->count(),
            'total_revenue' => Order::selesai()->sum('budget'),
        ];

        $recentOrders = Order::with('jastiper')->latest()->take(10)->get();
        $activeJastipers = User::activeJastipers()->withCount(['jastiperOrders as orders_count'])->get();

        return view('dashboard.admin', compact('stats', 'recentOrders', 'activeJastipers'));
    }

    public function adminOrders()
    {
        $orders = Order::with(['user', 'jastiper'])->latest()->paginate(20);
        return view('dashboard.admin-orders', compact('orders'));
    }

    public function adminOrderDetail(Order $order)
    {
        $order->load(['user', 'jastiper', 'rating']);
        $jastipers = User::activeJastipers()->get();
        return view('dashboard.admin-order-detail', compact('order', 'jastipers'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,proses,otw,selesai,batal']);
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Status order berhasil diupdate!');
    }

    public function adminJastipers()
    {
        $jastipers = User::jastipers()->withCount(['jastiperOrders as orders_count'])->latest()->paginate(20);
        return view('dashboard.admin-jastipers', compact('jastipers'));
    }

    public function toggleJastiper(User $jastiper)
    {
        $newStatus = $jastiper->status == 'aktif' ? 'offline' : 'aktif';
        $jastiper->update(['status' => $newStatus]);
        return back()->with('success', 'Status jastiper diperbarui!');
    }

    public function adminUsers()
    {
        $users = User::where('role', 'user')->latest()->paginate(20);
        return view('dashboard.admin-users', compact('users'));
    }

    public function adminReports()
    {
        $data = [
            'orders_by_status' => Order::selectRaw('status, count(*) as total')->groupBy('status')->get(),
            'orders_by_kategori' => Order::selectRaw('kategori, count(*) as total')->groupBy('kategori')->get(),
            'monthly_orders' => Order::selectRaw('MONTH(created_at) as bulan, count(*) as total')
                ->whereYear('created_at', date('Y'))
                ->groupBy('bulan')
                ->get(),
        ];
        return view('dashboard.admin-reports', compact('data'));
    }

    public function adminSettings()
    {
        return view('dashboard.admin-settings');
    }

    // ==========================================
    // JASTIPER DASHBOARD
    // ==========================================

    public function jastiper()
    {
        $user = Auth::user();
        $jastiper = $user;

        $stats = [
            'today' => Order::where('jastiper_id', $user->id)->today()->count(),
            'total_done' => Order::where('jastiper_id', $user->id)->selesai()->count(),
            'rating' => $user->rating ?? 5.0,
            'monthly_earn' => Order::where('jastiper_id', $user->id)
                ->selesai()
                ->whereMonth('created_at', date('m'))
                ->sum('budget'),
        ];

        $pendingOrders = Order::where('status', 'pending')->latest()->get();
        $activeOrders = Order::where('jastiper_id', $user->id)->active()->latest()->get();

        return view('dashboard.jastiper', compact('jastiper', 'stats', 'pendingOrders', 'activeOrders'));
    }

    public function jastiperOrders()
    {
        $orders = Order::where('status', 'pending')->latest()->paginate(15);
        return view('dashboard.jastiper-orders', compact('orders'));
    }

    public function jastiperHistory()
    {
        $orders = Order::where('jastiper_id', Auth::id())->latest()->paginate(15);
        return view('dashboard.jastiper-history', compact('orders'));
    }

    public function jastiperEarnings()
    {
        $orders = Order::where('jastiper_id', Auth::id())->selesai()->latest()->get();
        $totalEarnings = $orders->sum('budget');
        return view('dashboard.jastiper-earnings', compact('orders', 'totalEarnings'));
    }

    public function jastiperProfile()
    {
        $user = Auth::user();
        return view('dashboard.jastiper-profile', compact('user'));
    }

    public function toggleStatus()
    {
        $user = Auth::user();
        $newStatus = $user->status == 'aktif' ? 'offline' : 'aktif';
        \App\Models\User::where('id', Auth::id())->update(['status' => $newStatus]);
        
        return back()->with('success', 'Status berhasil diubah ke ' . $newStatus);
    }

    public function acceptOrder(Request $request, Order $order)
    {
        $order->update([
            'jastiper_id' => Auth::id(),
            'status' => 'proses',
        ]);
        return back()->with('success', 'Order berhasil diterima!');
    }

    public function rejectOrder(Order $order)
    {
        // Hanya abaikan (tidak assign ke jastiper ini)
        return back()->with('info', 'Order dilewati.');
    }

    public function jastiperUpdateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:otw,selesai']);

       
        if ($order->jastiper_id != Auth::id()) {
            abort(403);
        }

        $order->update(['status' => $request->status]);


        // Update total order jastiper jika selesai
        if ($request->status == 'selesai') {
            \App\Models\User::where('id', Auth::id())->increment('total_order');
        }

        return back()->with('success', 'Status order diperbarui!');
    } 

    // ==========================================
    // ULASAN BINTANG
    // ==========================================
    public function storeRating(Request $request, Order $order)
    {
        $request->validate([
            'bintang' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        \App\Models\Rating::create([
            'order_id'    => $order->id,
            'user_id'     => \Illuminate\Support\Facades\Auth::id(),
            'jastiper_id' => $order->jastiper_id,
            'bintang'     => $request->bintang,
            'komentar'    => $request->komentar,
        ]);

        $jastiper = $order->jastiper;
        if ($jastiper) {
            $rataRataRating = \App\Models\Rating::where('jastiper_id', $jastiper->id)->avg('bintang');
            $jastiper->update(['rating' => $rataRataRating]);
        }

        return back()->with('success', 'Terima kasih! Ulasan bintang berhasil dikirim.');
    }

    // ==========================================
    // PELANGGAN / USER DASHBOARD
    // ==========================================
    public function userHistory()
    {
        // Tarik semua pesanan milik user yang sedang login
        $orders = Order::where('user_id', Auth::id())
                    ->latest()
                    ->paginate(10);
                    
        return view('dashboard.user-history', compact('orders'));
    }
}