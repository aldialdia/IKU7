@extends('layouts.app')

@section('title', 'Edit Akun Fakultas')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Akun: {{ $admin->name }}</h1>

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

            <form action="{{ route('rektorat.manajemen-fakultas.update', $admin->id) }}" method="POST">
                @csrf
                @method('PUT') <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $admin->name) }}" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $admin->email) }}" required>
                </div>

                <div class="mb-3">
                    <label for="id_fakultas" class="form-label">Fakultas</label>
                    <select class="form-select" id="id_fakultas" name="id_fakultas" required>
                        <option value="">Pilih Fakultas...</option>
                        @foreach ($fakultas as $fak)
                            <option value="{{ $fak->id_fakultas }}" {{ old('id_fakultas', $admin->id_fakultas) == $fak->id_fakultas ? 'selected' : '' }}>
                                {{ $fak->Nama_fakultas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <hr>
                <p class="text-muted">Kosongkan password jika tidak ingin mengubahnya.</p>
                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru (Opsional)</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
                
                <hr>
                <a href="{{ route('rektorat.manajemen-fakultas.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
@endsection