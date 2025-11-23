@extends('layouts.app')

@section('title', 'Detail Mata Kuliah: ' . $matakuliah->Nama_mk)

@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('fakultas.dashboard') }}">Dashboard Fakultas</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('fakultas.dashboard.departemen', $matakuliah->id_departemen) }}">
                {{ $matakuliah->departemen->Nama_departemen }}
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            {{ $matakuliah->Nama_mk }}
        </li>
      </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800 mb-0">Detail Mata Kuliah</h1>
        <div>
            @if ($matakuliah->verified == 'verified')
                <span class="badge bg-success fs-6">Status: Terverifikasi</span>
            @else
                <span class="badge bg-warning text-dark fs-6">Status: Belum Diverifikasi</span>
            @endif
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Mata Kuliah</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th style="width: 35%;">Kode MK</th>
                            <td>: {{ $matakuliah->Kode_mk }}</td>
                        </tr>
                        <tr>
                            <th>Nama Mata Kuliah</th>
                            <td>: {{ $matakuliah->Nama_mk }}</td>
                        </tr>
                        <tr>
                            <th>Departemen</th>
                            <td>: {{ $matakuliah->departemen->Nama_departemen }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless mb-0">
                         <tr>
                            <th style="width: 35%;">Dosen Pengampu</th>
                            <td>: {{ $matakuliah->dosen->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Semester</th>
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
                <p class="text-center text-danger my-3">Komponen penilaian belum diatur oleh Dosen.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" style="width: 100%;">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Komponen</th>
                                <th style="width: 20%;">Persentase (%)</th>
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
                <p class="text-center text-danger my-3">Dokumen pendukung belum di-upload oleh Dosen.</p>
            @else
                <ul class="list-group">
                    @foreach ($matakuliah->dokumenPendukung as $doc)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-file-earmark-text me-2 text-secondary"></i>
                                <a href="{{ Storage::url($doc->path_file) }}" target="_blank" class="text-decoration-none">
                                    {{ $doc->nama_file }}
                                </a>
                            </div>
                            <a href="{{ Storage::url($doc->path_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Lihat
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

</div>
@endsection