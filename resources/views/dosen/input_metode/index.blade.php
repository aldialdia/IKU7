@extends('layouts.app')

@section('title', 'Input Metode Pembelajaran')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Input Metode Pembelajaran</h1>

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

            <form action="{{ route('dosen.input_metode.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="fakultas" class="form-label">Fakultas</label>
                            <select class="form-select" id="fakultas" name="id_fakultas" required>
                                <option value="">Pilih Fakultas</option>
                                @foreach ($fakultas as $fak)
                                    <option value="{{ $fak->id_fakultas }}" 
                                        {{ (isset($old_input['id_fakultas']) && $old_input['id_fakultas'] == $fak->id_fakultas) ? 'selected' : '' }}>
                                        {{ $fak->Nama_fakultas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="departemen" class="form-label">Departemen</label>
                            <select class="form-select" id="departemen" name="id_departemen" required>
                                <option value="">Pilih Fakultas Dulu</option>
                                @foreach ($departemen as $dep)
                                    <option value="{{ $dep->id_departemen }}" 
                                        {{ (isset($old_input['id_departemen']) && $old_input['id_departemen'] == $dep->id_departemen) ? 'selected' : '' }}>
                                        {{ $dep->Nama_departemen }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <select class="form-select" id="semester" name="semester_mk" required>
                                <option value="">Pilih Semester</option>
                                @for ($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}" 
                                        {{ (isset($old_input['semester_mk']) && $old_input['semester_mk'] == $i) ? 'selected' : '' }}>
                                        Semester {{ $i }}
                                    </option>
                                @endfor
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

    @if (!empty($old_input)) <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Hasil Pencarian</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kode MK</th>
                            <th>Nama Mata Kuliah</th>
                            <th>Pengampu</th>
                            <th>Metode</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mataKuliahList as $mk)
                            <tr>
                                <td>{{ $mk->Kode_mk }}</td>
                                <td>{{ $mk->Nama_mk }}</td>
                                <td>
                                    @if ($mk->dosen)
                                        <span class="badge bg-success">{{ $mk->dosen->Nama ?? $mk->dosen->name }}</span>
                                    @else
                                        <span class="badge bg-info text-dark">Belum Diklaim</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($mk->Metode)
                                        <span class="badge bg-primary">{{ $mk->Metode }}</span>
                                    @else
                                        <span class="badge bg-secondary">- Belum Diatur -</span>
                                    @endif
                                </td>
                                
                                <td>
                                    @if(is_null($mk->user_id))
                                        <a href="{{ route('dosen.input_metode.edit', $mk->Kode_mk) }}" class="btn btn-sm btn-warning">
                                            Klaim & Atur Metode
                                        </a>
                                    @elseif($mk->user_id == Auth::id())
                                        <a href="{{ route('dosen.input_metode.edit', $mk->Kode_mk) }}" class="btn btn-sm btn-info">
                                            Edit Metode
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled>Sudah Diampu</button>
                                    @endif
                                </td>
                                </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Data mata kuliah tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    // Pastikan script ini dijalankan setelah DOM dimuat
    document.addEventListener('DOMContentLoaded', function() {
        
        document.getElementById('fakultas').addEventListener('change', function() {
            let fakultasId = this.value;
            let departemenSelect = document.getElementById('departemen');

            // Kosongkan departemen
            departemenSelect.innerHTML = '<option value="">Memuat...</option>';

            if (fakultasId) {
                // Ambil data departemen via API
                // Gunakan route() helper dari Blade untuk generate URL
                fetch(`{{ route('api.get_departemen') }}?id_fakultas=${fakultasId}`)
                    .then(response => response.json())
                    .then(data => {
                        departemenSelect.innerHTML = '<option value="">Pilih Departemen</option>';
                        data.forEach(function(departemen) {
                            departemenSelect.innerHTML += `<option value="${departemen.id_departemen}">${departemen.Nama_departemen}</option>`;
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        departemenSelect.innerHTML = '<option value="">Gagal memuat</option>';
                    });
            } else {
                departemenSelect.innerHTML = '<option value="">Pilih Fakultas Dulu</option>';
            }
        });

    });
</script>
@endsection