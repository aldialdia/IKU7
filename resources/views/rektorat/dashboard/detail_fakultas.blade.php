@extends('layouts.app')

@section('title', 'Detail Fakultas: ' . $fakultas->Nama_fakultas)

@section('content')
<div class="container-fluid">
    <a href="{{ route('rektorat.dashboard', ['semester' => $old_input['semester'] ?? null]) }}" class="btn btn-outline-primary btn-sm mb-3">
        &larr; Kembali ke Dashboard Utama
    </a>
    
    <h1 class="h3 mb-4 text-gray-800">Detail Fakultas: {{ $fakultas->Nama_fakultas }}</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('rektorat.dashboard.fakultas', $fakultas->id_fakultas) }}" method="GET">
                <div class="row">
                    <div class="col-md-10">
                        <label for="semester" class="form-label">Semester</label>
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

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Rekap Metode Pembelajaran Per Departemen</h6>
                </div>
                <div class="card-body">
                    <div style="width: 100%; height: 400px;">
                        <canvas id="barChartDepartemen"></canvas>
                    </div>
                    
                    <hr>

                    <h6 class="mt-4 mb-3 font-weight-bold text-primary">Tabel Rangkuman (Klik untuk Detail)</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Departemen</th>
                                    <th>Total PjBL</th>
                                    <th>Total CBM</th>
                                    <th>Total Biasa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($departemenData as $data)
                                    <tr>
                                        <td>{{ $data->Nama_departemen }}</td>
                                        <td>{{ $data->count_pjbl }}</td>
                                        <td>{{ $data->count_cbm }}</td>
                                        <td>{{ $data->count_biasa }}</td>
                                        <td>
                                            <a href="{{ route('rektorat.dashboard.departemen', [
                                                        'departemen' => $data->id_departemen, 
                                                        'semester' => $old_input['semester'] ?? null
                                                    ]) }}" class="btn btn-sm btn-primary">
                                                Lihat Detail Matkul
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    // Data dari Controller
    const barLabels = @json($barChart['labels']);
    const barDataPjBL = @json($barChart['data_pjbl']);
    const barDataCBM = @json($barChart['data_cbm']);
    const barDataBiasa = @json($barChart['data_biasa']);

    // Bar Chart
    const barCtx = document.getElementById('barChartDepartemen').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: barLabels,
            datasets: [
                {
                    label: 'PjBL',
                    data: barDataPjBL,
                    backgroundColor: 'rgb(255, 193, 7)', // Warning
                },
                {
                    label: 'CBM',
                    data: barDataCBM,
                    backgroundColor: 'rgb(13, 202, 240)', // Info
                },
                {
                    label: 'Biasa',
                    data: barDataBiasa,
                    backgroundColor: 'rgb(108, 117, 125)', // Secondary
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { stacked: false },
                y: { stacked: false, beginAtZero: true }
            }
        }
    });
});
</script>
@endsection