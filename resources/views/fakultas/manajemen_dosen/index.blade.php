@extends('layouts.app')

@section('title', 'Manajemen Akun Dosen')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Manajemen Akun Dosen</h1>
    <p class="mb-4">Daftar akun dosen untuk fakultas Anda.</p>

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
            <a href="{{ route('fakultas.manajemen-dosen.create') }}" class="btn btn-primary btn-sm">
                + Tambah Akun Dosen Baru
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dosenList as $dosen)
                            <tr>
                                <td>{{ $dosen->name }}</td>
                                <td>{{ $dosen->email }}</td>
                                <td>
                                    <a href="{{ route('fakultas.manajemen-dosen.edit', $dosen->id) }}" class="btn btn-sm btn-warning me-1">
                                        Edit
                                    </a>
                                    <form action="{{ route('fakultas.manajemen-dosen.destroy', $dosen->id) }}" method="POST"
                                          class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus akun ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Belum ada akun dosen di fakultas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection