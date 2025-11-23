@extends('layouts.app')

@section('title', 'Penilaian: ' . $matakuliah->Nama_mk)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Penilaian: {{ $matakuliah->Nama_mk }}</h1>
        <a href="{{ route('rektorat.dashboard.departemen', ['departemen' => $matakuliah->id_departemen]) }}" class="btn btn-secondary btn-sm">
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
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Input Nilai Mahasiswa</h6>
            <span class="badge bg-info text-white">Otomatis Hitung Bobot</span>
        </div>
        <div class="card-body">
            <form action="{{ route('rektorat.penilaian.store_nilai', $matakuliah->Kode_mk) }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th rowspan="2" class="align-middle">NIM</th>
                                <th rowspan="2" class="align-middle" style="min-width: 150px;">Nama</th>
                                
                                <th rowspan="2" class="align-middle bg-warning-subtle border-warning">
                                    Input Nilai Akhir (0-100)
                                </th>

                                @foreach ($komponen as $komp)
                                    <th class="text-muted small">{{ $komp->nama_komponen }}</th>
                                @endforeach
                                
                                <th rowspan="2" class="align-middle bg-light">Grade</th>
                            </tr>
                            <tr>
                                @foreach ($komponen as $komp)
                                    <th class="text-muted x-small" style="font-size: 0.75rem;">
                                        Bobot: {{ $komp->persentase }}%
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mahasiswaList as $krs)
                                <tr class="row-mahasiswa" data-id="{{ $krs->id }}">
                                    <td class="text-center">{{ $krs->mahasiswa->nim }}</td>
                                    <td>{{ $krs->mahasiswa->nama }}</td>
                                    
                                    <td class="bg-warning-subtle border-warning">
                                        <input type="number" 
                                               name="nilai_akhir[{{ $krs->id }}]" 
                                               value="{{ $krs->nilai_akhir }}" 
                                               class="form-control text-center fw-bold border-warning input-nilai-akhir"
                                               data-target="{{ $krs->id }}"
                                               min="0" max="100" step="0.01" 
                                               placeholder="0"
                                               required>
                                    </td>

                                    @foreach ($komponen as $komp)
                                        @php
                                            $nilaiAda = $krs->nilai->where('komponen_id', $komp->id)->first();
                                            $nilaiAngka = $nilaiAda ? $nilaiAda->nilai_angka : 0;
                                        @endphp
                                        <td style="min-width: 70px;">
                                            <input type="text" 
                                                   class="form-control form-control-sm text-center bg-light text-muted input-komponen-{{ $krs->id }}"
                                                   data-persen="{{ $komp->persentase }}"
                                                   value="{{ number_format($nilaiAngka, 2) }}" 
                                                   readonly 
                                                   disabled>
                                        </td>
                                    @endforeach

                                    <td class="text-center fw-bold text-white" 
                                        style="background-color: {{ 
                                            match($krs->nilai_huruf) {
                                                'A', 'A-' => '#198754',
                                                'B+', 'B', 'B-' => '#0d6efd',
                                                'C+', 'C' => '#ffc107',
                                                'D', 'E' => '#dc3545',
                                                default => '#6c757d'
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
                        <button type="submit" class="btn btn-success btn-lg shadow">
                            <i class="bi bi-save me-2"></i> Simpan & Distribusikan Nilai
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.input-nilai-akhir');

        inputs.forEach(input => {
            // Event saat mengetik
            input.addEventListener('input', function() {
                updateKomponen(this);
            });
        });

        function updateKomponen(inputElement) {
            const krsId = inputElement.getAttribute('data-target');
            const nilaiAkhir = parseFloat(inputElement.value) || 0;
            
            // Ambil semua input komponen yang terkait dengan baris mahasiswa ini
            const komponenInputs = document.querySelectorAll(`.input-komponen-${krsId}`);

            komponenInputs.forEach(kompInput => {
                const persen = parseFloat(kompInput.getAttribute('data-persen')) || 0;
                
                // Rumus: Nilai Akhir * (Persen / 100)
                const nilaiBobot = nilaiAkhir * (persen / 100);
                
                // Update nilai di tampilan
                kompInput.value = nilaiBobot.toFixed(2); // Tampilkan 2 desimal
            });
        }
    });
</script>
@endsection