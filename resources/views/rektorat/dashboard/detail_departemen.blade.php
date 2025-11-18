@extends('layouts.app')

@section('title', 'Detail Departemen: ' . $departemen->Nama_departemen)

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('rektorat.dashboard', ['semester' => $old_input['semester'] ?? null]) }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('rektorat.dashboard.fakultas', ['fakultas' => $departemen->fakultas->id_fakultas, 'semester' => $old_input['semester'] ?? null]) }}">
                {{ $departemen->fakultas->Nama_fakultas }}
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            {{ $departemen->Nama_departemen }}
        </li>
      </ol>
    </nav>
    
    <h1 class="h3 mb-4 text-gray-800">Detail Departemen: {{ $departemen->Nama_departemen }}</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('rektorat.dashboard.departemen', $departemen->id_departemen) }}" method="GET">
                <div class="row">
                    <div class="col-md-10">
                        <label for="semester" class="form-label">Filter Semester</label>
                        <select class="form-select" id="semester" name="semester">
                            <option value="">Semua Semester</option>
                            @for ($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ (isset($old_input['semester']) && $old_input['semester'] == $i) ? 'selected' : '' }}>
                                    Semester {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
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
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Kode MK</th>
                            <th>Nama Mata Kuliah</th>
                            <th>Dosen Pengampu</th>
                            <th>Metode</th>
                            <th>Status Verifikasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mataKuliahList as $mk)
                            <tr>
                                <td>{{ $mk->Kode_mk }}</td>
                                <td>{{ $mk->Nama_mk }}</td>
                                <td>{{ $mk->dosen->name ?? '-' }}</td>
                                <td>
                                    @if ($mk->Metode)
                                        <span class="badge bg-primary">{{ $mk->Metode }}</span>
                                    @else
                                        <span class="badge bg-secondary">- Belum Diatur -</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($mk->verified == 'verified')
                                        <span class="badge bg-success">Sudah Diverifikasi</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum Diverifikasi</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('dosen.matkul_saya.show', $mk->Kode_mk) }}" 
                                       class="btn btn-sm btn-info" target="_blank">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada mata kuliah untuk departemen/semester ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection