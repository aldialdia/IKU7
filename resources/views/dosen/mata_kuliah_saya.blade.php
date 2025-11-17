@extends('layouts.app') @section('title', 'Mata Kuliah Saya')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Mata Kuliah yang Diampu</h1>

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
                <h6 class="m-0 font-weight-bold text-primary">Daftar Mata Kuliah</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Kode MK</th>
                                <th>Nama Mata Kuliah</th>
                                <th>Semester</th>
                                <th>SKS</th>
                                <th>Metode Pembelajaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($daftarMataKuliah as $mk)
                                <tr>
                                    <td>{{ $mk->Kode_mk }}</td>
                                    <td>{{ $mk->Nama_mk }}</td>
                                    <td>{{ $mk->Semester_mk }}</td>
                                    <td>{{ $mk->SKS }}</td>
                                    <td>
                                        @if ($mk->Metode)
                                            <span class="badge bg-primary">{{ $mk->Metode }}</span>
                                        @else
                                            <span class="badge bg-secondary">- Belum Diatur -</span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <a href="{{ route('dosen.matkul_saya.show', $mk->Kode_mk) }}" class="btn btn-sm btn-primary me-1">
                                            Detail
                                        </a>

                                        <form action="{{ route('dosen.matkul_saya.reset', $mk->Kode_mk) }}" method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Anda yakin ingin mereset mata kuliah ini? Klaim Anda dan semua komponen penilaian akan dihapus.')">
                                            
                                            @csrf
                                            @method('DELETE')
                                            
                                            <button type="submit" class="btn btn-sm btn-danger">Reset</button>
                                        </form>
                                    </td>
                                    </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        Anda belum mengampu mata kuliah apapun.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection