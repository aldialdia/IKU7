@extends('layouts.app')

@section('title', 'Dashboard Rektorat')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Monitoring Metode Pembelajaran</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('rektorat.dashboard') }}" method="GET">
                <div class="row">
                    <div class="col-md-5">
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
                    <div class="col-md-5">
                        <label for="tahun" class="form-label">-</label>
                        <select class="form-select" id="tahun" name="tahun" disabled>
                            <option value="">-</option>
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
        <div class="col-xl-8 col-md-12">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card border-primary shadow h-100 py-2">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Total Mata Kuliah</h5>
                            <h2 class="fw-bold">{{ $kpi['totalMatkul'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card border-warning shadow h-100 py-2">
                        <div class="card-body">
                            <h5 class="card-title text-warning">Project Based Learning</h5>
                            <h2 class="fw-bold">{{ $kpi['totalPjBL'] }}</h2>
                        </div>
                    </div>
                </div>
                 <div class="col-md-3 mb-4">
                    <div class="card border-info shadow h-100 py-2">
                        <div class="card-body">
                            <h5 class="card-title text-info">Case Based Method</h5>
                            <h2 class="fw-bold">{{ $kpi['totalCBM'] }}</h2>
                        </div>
                    </div>
                </div>
                 <div class="col-md-3 mb-4">
                    <div class="card border-secondary shadow h-100 py-2">
                        <div class="card-body">
                            <h5 class="card-title text-secondary">Metode Biasa</h5>
                            <h2 class="fw-bold">{{ $kpi['totalBiasa'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Presentase Metode</h6>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div style="max-height:250px;">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Presentase Metode Pembelajaran Per Fakultas</h6>
                </div>
                <div class="card-body">
                    <div style="width: 100%; height: 400px;">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        </div>

    <div class="row">
        </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    

                    <h6 class="mt-4 mb-3 font-weight-bold text-primary">Tabel Rangkuman (Klik untuk Detail)</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Fakultas</th>
                                    <th>Total PjBL</th>
                                    <th>Total CBM</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barChartData as $data)
                                    <tr>
                                        <td>{{ $data->Nama_fakultas }}</td>
                                        <td>{{ $data->count_pjbl }}</td>
                                        <td>{{ $data->count_cbm }}</td>
                                        <td>
                                            <a href="{{ route('rektorat.dashboard.fakultas', ['fakultas' => $data->id_fakultas, 'semester' => $old_input['semester'] ?? null]) }}" 
                                               class="btn btn-sm btn-primary">
                                                Lihat Detail Departemen
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

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    // Data dari Controller
    const pieData = @json($pieChartData['data']);
    const pieLabels = @json($pieChartData['labels']);
    
    const barChartData = @json($barChartData);
    const barLabels = barChartData.map(item => item.Nama_fakultas);
    const barDataPjBL = barChartData.map(item => item.count_pjbl);
    const barDataCBM = barChartData.map(item => item.count_cbm);
    // 1. Pie Chart
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: pieLabels,
            datasets: [{
                data: pieData,
                backgroundColor: [
                    'rgb(255, 193, 7)',  // Warning (Kuning untuk PjBL)
                    'rgb(13, 202, 240)', // Info (Biru untuk CBM)
                    'rgb(108, 117, 125)' // Secondary (Abu-abu untuk Biasa)
                ],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });

    // 2. Bar Chart
    const barCtx = document.getElementById('barChart').getContext('2d');
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
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: false
                },
                y: {
                    stacked: false,
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection