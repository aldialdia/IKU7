<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SesiController extends Controller
{
    function index() 
    {
        return view('login');
    }

    function login(Request $request) 
    {
        $request ->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi'
        ]);

        $infologin =
        [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(Auth::attempt($infologin)) {
            if(Auth::user()->role == 'rektorat') {
                return redirect()->route('rektorat.dashboard');
            } else if(Auth::user()->role == 'fakultas') {
                return redirect()->route('fakultas.dashboard');
            } else if(Auth::user()->role == 'dosen') {
                return redirect()->route('dosen.dashboard');
            }
        }else {
            return redirect('')->withErrors('Email atau Password salah')->withInput();
        }

    }

    function logout() 
    {
        Auth::logout();
        return redirect('');
    }
}
