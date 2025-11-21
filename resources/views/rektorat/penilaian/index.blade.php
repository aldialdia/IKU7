@extends('layouts.app')

@section('title', 'Penilaian: ' . $matakuliah->Nama_mk)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Penilaian: {{ $matakuliah->Nama_mk }}</h1>
        <a href="{{ route('rektorat.penilaian.list') }}" class="btn btn-secondary btn-sm">
            &larr; Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Mahasiswa ke Kelas Ini</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('rektorat.penilaian.store_mahasiswa', $matakuliah->Kode_mk) }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <input type="text" name="nim" class="form-control" placeholder="NIM Mahasiswa" required>
                </div>
                <div class="col-md-5">
                    <input type="text" name="nama" class="form-control" placeholder="Nama Mahasiswa" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">+ Tambah</button>
                </div>
            </form>
            <small class="text-muted">*Jika NIM sudah ada di database master, nama akan otomatis menyesuaikan.</small>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Input Nilai Mahasiswa</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('rektorat.penilaian.store_nilai', $matakuliah->Kode_mk) }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th rowspan="2" class="align-middle">NIM</th>
                                <th rowspan="2" class="align-middle">Nama</th>
                                @foreach ($komponen as $komp)
                                    <th>{{ $komp->nama_komponen }}</th>
                                @endforeach
                                <th rowspan="2" class="align-middle bg-light">Nilai Akhir</th>
                                <th rowspan="2" class="align-middle bg-light">Grade</th>
                            </tr>
                            <tr>
                                @foreach ($komponen as $komp)
                                    <th class="small text-muted">{{ $komp->persentase }}%</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mahasiswaList as $krs)
                                <tr>
                                    <td>{{ $krs->mahasiswa->nim }}</td>
                                    <td>{{ $krs->mahasiswa->nama }}</td>
                                    
                                    @foreach ($komponen as $komp)
                                        @php
                                            // Cari nilai yang sudah tersimpan (jika ada) untuk krs ini & komponen ini
                                            $nilaiAda = $krs->nilai->where('komponen_id', $komp->id)->first();
                                            $nilaiAngka = $nilaiAda ? $nilaiAda->nilai_angka : 0;
                                        @endphp
                                        <td style="min-width: 80px;">
                                            <input type="number" 
                                                   name="nilai[{{ $krs->id }}][{{ $komp->id }}]" 
                                                   value="{{ $nilaiAngka }}" 
                                                   class="form-control form-control-sm text-center"
                                                   min="0" max="100" step="0.01">
                                        </td>
                                    @endforeach

                                    <td class="text-center fw-bold bg-light">
                                        {{ number_format($krs->nilai_akhir, 2) }}
                                    </td>
                                    
                                    <td class="text-center fw-bold text-white" 
                                        style="background-color: {{ 
                                            match($krs->nilai_huruf) {
                                                'A', 'A-' => '#198754', // Hijau
                                                'B+', 'B', 'B-' => '#0d6efd', // Biru
                                                'C+', 'C' => '#ffc107', // Kuning (text-dark diatur manual jika perlu)
                                                'D', 'E' => '#dc3545', // Merah
                                                default => '#6c757d' // Abu-abu
                                            }
                                        }};">
                                        {{ $krs->nilai_huruf ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $komponen->count() + 4 }}" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bi bi-people text-muted mb-2" style="font-size: 2rem;"></i>
                                            <p class="text-muted mb-0">Belum ada mahasiswa di kelas ini.</p>
                                            <small>Silakan tambahkan mahasiswa menggunakan form di atas.</small>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($mahasiswaList->isNotEmpty())
                    <div class="mt-4 d-flex justify-content-end">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-save me-2"></i>Simpan & Hitung Nilai
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection