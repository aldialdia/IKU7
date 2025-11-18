@extends('layouts.app')

@section('title', 'Tambah Akun Fakultas')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tambah Akun Admin Fakultas Baru</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('rektorat.manajemen-fakultas.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label for="id_fakultas" class="form-label">Fakultas</label>
                    <select class="form-select" id="id_fakultas" name="id_fakultas" required>
                        <option value="">Pilih Fakultas...</option>
                        @foreach ($fakultas as $fak)
                            <option value="{{ $fak->id_fakultas }}" {{ old('id_fakultas') == $fak->id_fakultas ? 'selected' : '' }}>
                                {{ $fak->Nama_fakultas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
                
                <hr>
                <a href="{{ route('rektorat.manajemen-fakultas.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Akun</button>
            </form>
        </div>
    </div>
</div>
@endsection