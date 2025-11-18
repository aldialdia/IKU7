@extends('layouts.app')

@section('title', 'Manajemen Akun Fakultas')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Manajemen Akun Fakultas</h1>
    <p class="mb-4">Daftar akun admin untuk setiap fakultas.</p>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{ route('rektorat.manajemen-fakultas.create') }}" class="btn btn-primary btn-sm">
                + Tambah Akun Fakultas Baru
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Fakultas yang Diampu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($adminList as $admin)
                            <tr>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>
                                    {{ $admin->fakultas->Nama_fakultas ?? 'N/A' }}
                                </td>
                                <td>
                                    <a href="{{ route('rektorat.manajemen-fakultas.edit', $admin->id) }}" class="btn btn-sm btn-warning me-1">
                                        Edit
                                    </a>
                                    <form action="{{ route('rektorat.manajemen-fakultas.destroy', $admin->id) }}" method="POST"
                                          class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus akun ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada akun Admin Fakultas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection