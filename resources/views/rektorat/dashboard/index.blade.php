@extends('layouts.app')

@section('title', 'Dashboard Rektorat')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Monitoring Metode Pembelajaran</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('rektorat.dashboard') }}" method="GET">
                <div class="row">
                    <div class="col-md-10">
                        <label for="semester" class="form-label">Tahun Akademik / Periode</label>
                        <select class="form-select" id="semester" name="semester" onchange="this.form.submit()">
                            <option value="">Semua Periode</option>
                            @php
                                $tahunAkademik = ['Ganjil 2024/2025', 'Genap 2024/2025', 'Ganjil 2023/2024', 'Genap 2023/2024'];
                            @endphp
                            @foreach ($tahunAkademik as $th)
                                <option value="{{ $th }}" {{ (isset($old_input['semester']) && $old_input['semester'] == $th) ? 'selected' : '' }}>{{ $th }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <a href="{{ route('rektorat.dashboard') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-md-12">
            <div class="row">
                <div class="col-md-3 mb-4"><div class="card border-primary shadow h-100 py-2"><div class="card-body"><h5 class="card-title text-primary">Total Matkul</h5><h2 class="fw-bold">{{ $kpi['totalMatkul'] }}</h2></div></div></div>
                <div class="col-md-3 mb-4"><div class="card border-warning shadow h-100 py-2"><div class="card-body"><h5 class="card-title text-warning">PjBL</h5><h2 class="fw-bold">{{ $kpi['totalPjBL'] }}</h2></div></div></div>
                <div class="col-md-3 mb-4"><div class="card border-info shadow h-100 py-2"><div class="card-body"><h5 class="card-title text-info">CBM</h5><h2 class="fw-bold">{{ $kpi['totalCBM'] }}</h2></div></div></div>
                <div class="col-md-3 mb-4"><div class="card border-secondary shadow h-100 py-2"><div class="card-body"><h5 class="card-title text-secondary">Biasa</h5><h2 class="fw-bold">{{ $kpi['totalBiasa'] }}</h2></div></div></div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Presentase Metode</h6></div>
                <div class="card-body d-flex justify-content-center align-items-center"><div style="max-height:250px;"><canvas id="pieChart"></canvas></div></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Metode Pembelajaran Per Fakultas</h6>
                </div>
                <div class="card-body">
                    <div style="width: 100%; height: 400px;">
                        <canvas id="barChart"></canvas>
                    </div>
                    
                    <hr>

                    <h6 class="mt-4 mb-3 font-weight-bold text-primary">Tabel Rangkuman (Klik untuk Detail)</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Fakultas</th>
                                    <th>Total PjBL</th>
                                    <th>Total CBM</th>
                                    <th>Total Biasa</th> <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barChartData as $data)
                                    <tr>
                                        <td>{{ $data->Nama_fakultas }}</td>
                                        <td>{{ $data->count_pjbl }}</td>
                                        <td>{{ $data->count_cbm }}</td>
                                        <td>{{ $data->count_biasa }}</td> <td>
                                            <a href="{{ route('rektorat.dashboard.fakultas', ['fakultas' => $data->id_fakultas, 'semester' => $old_input['semester'] ?? null]) }}" 
                                               class="btn btn-sm btn-primary">
                                                Lihat Detail Fakultas
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
    const pieData = @json($pieChartData['data']);
    const pieLabels = @json($pieChartData['labels']);
    
    // --- PERSIAPAN DATA BAR CHART (UPDATED) ---
    const barChartData = @json($barChartData);
    const barLabels = barChartData.map(item => item.Nama_fakultas);
    const barDataPjBL = barChartData.map(item => item.count_pjbl);
    const barDataCBM = barChartData.map(item => item.count_cbm);
    const barDataBiasa = barChartData.map(item => item.count_biasa); // Tambah ini
    // ------------------------------------------

    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: pieLabels,
            datasets: [{
                data: pieData,
                backgroundColor: ['rgb(255, 193, 7)', 'rgb(13, 202, 240)', 'rgb(108, 117, 125)'],
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: barLabels,
            datasets: [
                { label: 'PjBL', data: barDataPjBL, backgroundColor: 'rgb(255, 193, 7)' }, // Kuning
                { label: 'CBM', data: barDataCBM, backgroundColor: 'rgb(13, 202, 240)' },  // Biru
                { label: 'Biasa', data: barDataBiasa, backgroundColor: 'rgb(108, 117, 125)' } // Abu-abu
            ]
        },
        options: { responsive: true, maintainAspectRatio: false, scales: { x: { stacked: false }, y: { stacked: false, beginAtZero: true } } }
    });
});
</script>
@endsection