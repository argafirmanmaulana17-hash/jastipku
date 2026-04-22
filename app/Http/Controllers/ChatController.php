<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat.index');
    }
}
