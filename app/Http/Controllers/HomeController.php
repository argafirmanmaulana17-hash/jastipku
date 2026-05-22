<?php

// ============================================
// HomeController.php
// ============================================

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $activeJastipers = User::activeJastipers()->take(3)->get();

        return view('home', compact('activeJastipers'));
    }
}
