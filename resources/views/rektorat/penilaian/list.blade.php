@extends('layouts.app')

@section('title', 'Daftar Penilaian Mahasiswa')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Penilaian Mahasiswa</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Cari Mata Kuliah</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('rektorat.penilaian.list') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fakultas</label>
                            <select name="id_fakultas" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Fakultas</option>
                                @foreach ($fakultas as $fak)
                                    <option value="{{ $fak->id_fakultas }}"
                                        {{ isset($old_input['id_fakultas']) && $old_input['id_fakultas'] == $fak->id_fakultas ? 'selected' : '' }}>
                                        {{ $fak->Nama_fakultas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Departemen</label>
                            <select name="id_departemen" class="form-select">
                                <option value="">Semua Departemen</option>
                                @foreach ($departemen as $dep)
                                    <option value="{{ $dep->id_departemen }}"
                                        {{ isset($old_input['id_departemen']) && $old_input['id_departemen'] == $dep->id_departemen ? 'selected' : '' }}>
                                        {{ $dep->Nama_departemen }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
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

                        <div class="col-md-2 d-flex align-items-end mb-3">
                            <button type="submit" class="btn btn-primary w-100"> <i class="bi bi-search me-1"></i>
                                Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kode MK</th>
                                <th>Mata Kuliah</th>
                                <th>Departemen</th>
                                <th>Semester</th>
                                <th>Status Verifikasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mataKuliahList as $mk)
                                <tr>
                                    <td>{{ $mk->Kode_mk }}</td>
                                    <td>{{ $mk->Nama_mk }}</td>
                                    <td>{{ $mk->departemen->Nama_departemen ?? '-' }}</td>
                                    <td>{{ $mk->Semester_mk }}</td>

                                    <td>
                                        @if ($mk->verified == 'verified')
                                            <span class="badge bg-success">Terverifikasi</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Belum Diverifikasi</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($mk->verified == 'verified')
                                            <a href="{{ route('rektorat.penilaian.index', $mk->Kode_mk) }}"
                                                class="btn btn-sm btn-success">
                                                <i class="bi bi-pencil-square me-1"></i> Input Nilai
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-secondary disabled" disabled
                                                title="Matkul belum diverifikasi fakultas">
                                                <i class="bi bi-lock-fill me-1"></i> Terkunci
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data mata kuliah.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    {{ $mataKuliahList->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
