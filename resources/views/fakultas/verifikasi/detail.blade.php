@extends('layouts.app')

@section('title', 'Detail Verifikasi - ' . $matakuliah->Nama_mk)

@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')
<div class="container-fluid">
    <a href="{{ route('fakultas.verifikasi.index') }}" class="btn btn-outline-primary btn-sm mb-3">
        &larr; Kembali ke Daftar Verifikasi
    </a>

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
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                Detail Mata Kuliah: {{ $matakuliah->Nama_mk }}
            </h6>
            @if ($matakuliah->verified == 'verified')
                <span class="badge bg-success">Sudah Diverifikasi</span>
            @else
                <span class="badge bg-warning text-dark">Belum Diverifikasi</span>
            @endif
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 30%;">Kode MK</th>
                            <td>: {{ $matakuliah->Kode_mk }}</td>
                        </tr>
                        <tr>
                            <th>Nama Mata Kuliah</th>
                            <td>: {{ $matakuliah->Nama_mk }}</td>
                        </tr>
                        <tr>
                            <th>Fakultas</th>
                            <td>: {{ $matakuliah->departemen->fakultas->Nama_fakultas }}</td>
                        </tr>
                        <tr>
                            <th>Departemen</th>
                            <td>: {{ $matakuliah->departemen->Nama_departemen }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                         <tr>
                            <th style="width: 30%;">Dosen Pengampu</th>
                            <td>: {{ $matakuliah->dosen->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th style="width: 30%;">Semester</th>
                            <td>: {{ $matakuliah->Semester_mk }}</td>
                        </tr>
                        <tr>
                            <th>SKS</th>
                            <td>: {{ $matakuliah->SKS }}</td>
                        </tr>
                        <tr>
                            <th>Metode</th>
                            <td>
                                @if ($matakuliah->Metode)
                                    : <span class="badge bg-primary">{{ $matakuliah->Metode }}</span>
                                @else
                                    : <span class="badge bg-secondary">- Belum Diatur -</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Komponen Penilaian</h6>
        </div>
        <div class="card-body">
            @if($matakuliah->komponenPenilaian->isEmpty())
                <p class="text-center text-danger">Komponen penilaian belum diatur oleh Dosen.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" width="50%">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Komponen</th>
                                <th>Persentase (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($matakuliah->komponenPenilaian as $komponen)
                                <tr>
                                    <td>{{ $komponen->nama_komponen }}</td>
                                    <td>{{ $komponen->persentase }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th class="text-end">Total</th>
                                <th>{{ $matakuliah->komponenPenilaian->sum('persentase') }}%</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Dokumen Pendukung</h6>
        </div>
        <div class="card-body">
            @if($matakuliah->dokumenPendukung->isEmpty())
                <p class="text-center text-danger">Dokumen pendukung (10 file) belum di-upload oleh Dosen.</p>
            @else
                <ul class="list-group">
                    @foreach ($matakuliah->dokumenPendukung as $doc)
                        <li class="list-group-item">
                            <a href="{{ Storage::url($doc->path_file) }}" target="_blank">
                                {{ $doc->nama_file }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Aksi Verifikasi</h6>
        </div>
        <div class="card-body">
            @if ($matakuliah->verified == 'verified')
                <div class="alert alert-success text-center">
                    Mata kuliah ini sudah diverifikasi.
                </div>
            @else
                <form action="{{ route('fakultas.verifikasi.verify', $matakuliah->Kode_mk) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    @if ($tombolVerifikasiAktif)
                        <div class="alert alert-info">
                            Komponen penilaian dan dokumen pendukung sudah lengkap. Mata kuliah siap diverifikasi.
                        </div>
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            VERIFIKASI SEKARANG
                        </button>
                    @else
                        <div class="alert alert-warning">
                            <strong>Tombol verifikasi belum aktif.</strong> Dosen harus melengkapi data berikut:
                            <ul>
                                @if ($matakuliah->komponenPenilaian->isEmpty())
                                    <li>Komponen Penilaian (masih kosong)</li>
                                @endif
                                @if ($matakuliah->dokumenPendukung->isEmpty())
                                    <li>Dokumen Pendukung (masih kosong)</li>
                                @endif
                            </ul>
                        </div>
                        <button type="submit" class="btn btn-secondary btn-lg w-100" disabled>
                            VERIFIKASI SEKARANG
                        </button>
                    @endif
                </form>
            @endif
        </div>
    </div>

</div>
@endsection