@extends('layouts.app')

@section('title', 'Verifikasi Mata Kuliah')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Verifikasi Metode Mata Kuliah</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filter Mata Kuliah</h6>
            </div>
            <div class="card-body">
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

                <form action="{{ route('fakultas.verifikasi.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label for="departemen" class="form-label">Departemen</label>
                                <select class="form-select" id="departemen" name="id_departemen">
                                    <option value="">Semua Departemen</option>
                                    @foreach ($departemenList as $dep)
                                        <option value="{{ $dep->id_departemen }}"
                                            {{ isset($old_input['id_departemen']) && $old_input['id_departemen'] == $dep->id_departemen ? 'selected' : '' }}>
                                            {{ $dep->Nama_departemen }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="mb-3">
                                <label class="form-label">Tahun Akademik</label>
                                <select class="form-select" id="semester" name="semester_mk">
                                    <option value="">Semua Periode</option>

                                    @php
                                        // Daftar Tahun Akademik (Bisa ditambah sesuai kebutuhan)
                                        $tahunAkademik = [
                                            'Ganjil 2024/2025',
                                            'Genap 2024/2025',
                                            'Ganjil 2023/2024',
                                            'Genap 2023/2024',
                                        ];
                                    @endphp

                                    @foreach ($tahunAkademik as $th)
                                        <option value="{{ $th }}"
                                            {{ isset($old_input['semester_mk']) && $old_input['semester_mk'] == $th ? 'selected' : '' }}>
                                            {{ $th }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 d-flex align-items-end mb-3">
                            <button type="submit" class="btn btn-primary w-100">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Mata Kuliah</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kode MK</th>
                                <th>Nama Mata Kuliah</th>
                                <th>Departemen</th>
                                <th>Dosen Pengampu</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mataKuliahList as $mk)
                                <tr>
                                    <td>{{ $mk->Kode_mk }}</td>
                                    <td>{{ $mk->Nama_mk }}</td>
                                    <td>{{ $mk->departemen->Nama_departemen ?? '-' }}</td>
                                    <td>{{ $mk->dosen->name ?? '-' }}</td>
                                    <td>
                                        @if ($mk->verified == 'verified')
                                            <span class="badge bg-success">Sudah Diverifikasi</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Belum Diverifikasi</span>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('fakultas.verifikasi.show', $mk->Kode_mk) }}"
                                            class="btn btn-sm btn-primary">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        Tidak ada data mata kuliah yang sesuai dengan filter.
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
