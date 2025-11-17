@extends('layouts.app') @section('title', 'Dashboard Admin') @section('content')
    
    <h1>Halo, {{ Auth::user()->name ?? Auth::user()->Nama }}!</h1>
    <div>Selamat datang di halaman dashboard.</div>

    <div class="card mt-3">
        <div class="card-header">
            Menu Cepat (Berdasarkan Role)
        </div>
        <ul class="list-group list-group-flush">
            @if (auth()->user()->role == 'rektorat')
            <li class="list-group-item">Menu Rektorat</li>
            @endif
            @if (auth()->user()->role == 'fakultas')
            <li class="list-group-item">Menu Fakultas</li>
            @endif
            @if (auth()->user()->role == 'dosen')
            <li class="list-group-item">Menu Dosen</li>
            @endif
        </ul>
    </div>

@endsection