@extends('layouts.app')

@section('title', 'Atur Metode - ' . $matakuliah->Nama_mk)

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')
<div class="container-fluid" x-data="komponenPenilaian()">
    <h1 class="h3 mb-2 text-gray-800">Atur Metode Pembelajaran</h1>
    <p class="mb-4">Mata Kuliah: <strong>{{ $matakuliah->Nama_mk }} ({{ $matakuliah->Kode_mk }})</strong></p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('dosen.input_metode.update', $matakuliah->Kode_mk) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">1. Pilih Metode Pembelajaran</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="metode" class="form-label">Metode Pembelajaran</label>
                    <select class="form-select" id="metode" name="metode" x-model="metodeTerpilih">
                        <option value="Biasa" {{ $matakuliah->Metode == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                        <option value="PjBL" {{ $matakuliah->Metode == 'PjBL' ? 'selected' : '' }}>Project Based Learning (PjBL)</option>
                        <option value="CBM" {{ $matakuliah->Metode == 'CBM' ? 'selected' : '' }}>Case Based Method (CBM)</option>
                    </select>
                </div>
                <div x-show="metodeTerpilih === 'PjBL' || metodeTerpilih === 'CBM'" class="alert alert-warning">
                    Pastikan komponen <strong>"Proyek"</strong> memiliki persentase <strong>minimal 50%</strong>.
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">2. Atur Komponen Penilaian</h6>
                <h6 class="m-0 font-weight-bold" :class="totalPersen == 100 ? 'text-success' : 'text-danger'">
                    Total: <span x-text="totalPersen"></span>%
                </h6>
            </div>
            <div class="card-body">
                <template x-for="(komponen, index) in daftarKomponen" :key="index">
                    <div class="row mb-2 align-items-center">
                        <div class="col-md-6">
                            <label class="form-label">Nama Komponen</label>
                            <input type="text" class="form-control" :name="`komponen[${index}][nama]`" x-model="komponen.nama">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Persentase (%)</label>
                            <input type="number" class="form-control" :name="`komponen[${index}][persen]`" x-model.number="komponen.persen" @input="hitungTotal()">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-danger" @click="hapusKomponen(index)" :disabled="daftarKomponen.length <= 1">
                                Hapus
                            </button>
                        </div>
                    </div>
                </template>
                <hr>
                <button type="button" class="btn btn-success" @click="tambahKomponen()">
                    + Tambah Komponen
                </button>
            </div>
        </div>
        
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">3. Dokumen Pendukung</h6>
            </div>
            <div class="card-body">
                
                <h6 class="mb-3">Dokumen Terupload:</h6>
                @if ($matakuliah->dokumenPendukung->isEmpty())
                    <p>Belum ada dokumen yang di-upload.</p>
                @else
                    <ul>
                        @foreach ($matakuliah->dokumenPendukung as $doc)
                            <li>
                                <a href="{{ Storage::url($doc->path_file) }}" target="_blank">{{ $doc->nama_file }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
                
                <hr>

                <div class="mb-3">
                    <label for="dokumen" class="form-label">Upload Dokumen Baru (Pilih 10 file)</label>
                    <input class="form-control" type="file" id="dokumen" name="dokumen_pendukung[]" multiple>
                </div>
                <small class="text-muted">
                    Aturan: Jika Anda ingin menambah file, Anda harus memilih **tepat 10 file** sekaligus.<br>
                    File yang sudah ada tidak akan terhapus saat Anda meng-upload file baru.
                </small>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mb-4">
            Simpan Data
        </button>

    </form> 
</div>

<script>
    function komponenPenilaian() {
        return {
            metodeTerpilih: '{{ old('metode', $matakuliah->Metode ?? 'Biasa') }}',
            totalPersen: 0,
            daftarKomponen: @json(old('komponen', $komponen->map(fn($k) => ['nama' => $k->nama_komponen, 'persen' => $k->persentase]))) ,
            
            init() {
                this.hitungTotal();
            },
            hitungTotal() {
                this.totalPersen = this.daftarKomponen.reduce((total, komponen) => {
                    return total + (parseInt(komponen.persen) || 0);
                }, 0);
            },
            tambahKomponen() {
                this.daftarKomponen.push({
                    nama: '',
                    persen: 0
                });
                this.hitungTotal();
            },
            hapusKomponen(index) {
                if (this.daftarKomponen.length > 1) {
                    this.daftarKomponen.splice(index, 1);
                    this.hitungTotal();
                }
            }
        }
    }
</script>
@endsection