<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        // Auth Facadesでよく使用するmehtod
        // dd(Auth::id());
        // dd(Auth::user());
        // dd(Auth::check());
        return view('home');
    }

    public function contact()
    {
        return view('contact');
    }

    public function secret()
    {
        return view('secret');
    }
}
