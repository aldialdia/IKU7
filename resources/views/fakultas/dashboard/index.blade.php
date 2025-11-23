@extends('layouts.app')

@section('title', 'Dashboard Fakultas')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dashboard Fakultas</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('fakultas.dashboard') }}" method="GET">
                <div class="row">
                    <div class="col-md-10">
                        <label class="form-label">Semester</label>
                        <select class="form-select" name="semester" onchange="this.form.submit()">
                            <option value="">Semua Semester</option>
                            @for ($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ (isset($old_input['semester']) && $old_input['semester'] == $i) ? 'selected' : '' }}>Semester {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <a href="{{ route('fakultas.dashboard') }}" class="btn btn-secondary w-100">Reset</a>
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
                        <div class="card-body"><h5 class="card-title text-primary">Total Matkul</h5><h2 class="fw-bold">{{ $kpi['totalMatkul'] }}</h2></div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card border-warning shadow h-100 py-2">
                        <div class="card-body"><h5 class="card-title text-warning">PjBL</h5><h2 class="fw-bold">{{ $kpi['totalPjBL'] }}</h2></div>
                    </div>
                </div>
                 <div class="col-md-3 mb-4">
                    <div class="card border-info shadow h-100 py-2">
                        <div class="card-body"><h5 class="card-title text-info">CBM</h5><h2 class="fw-bold">{{ $kpi['totalCBM'] }}</h2></div>
                    </div>
                </div>
                 <div class="col-md-3 mb-4">
                    <div class="card border-secondary shadow h-100 py-2">
                        <div class="card-body"><h5 class="card-title text-secondary">Biasa</h5><h2 class="fw-bold">{{ $kpi['totalBiasa'] }}</h2></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Presentase Metode</h6></div>
                <div class="card-body d-flex justify-content-center"><div style="max-height:250px;"><canvas id="pieChart"></canvas></div></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Metode Pembelajaran Per Departemen</h6></div>
                <div class="card-body">
                    <div style="height: 400px;"><canvas id="barChart"></canvas></div>
                    
                    <hr class="my-4">

                    <h6 class="mb-3 font-weight-bold text-primary">Tabel Rangkuman (Klik untuk Detail)</h6>
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
                                @foreach ($barChartData as $data)
                                    <tr>
                                        <td>{{ $data->Nama_departemen }}</td>
                                        <td>{{ $data->count_pjbl }}</td>
                                        <td>{{ $data->count_cbm }}</td>
                                        <td>{{ $data->count_biasa }}</td>
                                        <td>
                                            <a href="{{ route('fakultas.dashboard.departemen', ['departemen' => $data->id_departemen, 'semester' => $old_input['semester'] ?? null]) }}" 
                                               class="btn btn-sm btn-primary">
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
document.addEventListener('DOMContentLoaded', () => {
    const pieData = @json($pieChartData['data']);
    const pieLabels = @json($pieChartData['labels']);
    const barLabels = @json($barChart['labels']);
    const barPjBL = @json($barChart['data_pjbl']);
    const barCBM = @json($barChart['data_cbm']);

    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: { labels: pieLabels, datasets: [{ data: pieData, backgroundColor: ['#ffc107', '#0dcaf0', '#6c757d'] }] },
        options: { responsive: true, maintainAspectRatio: false }
    });

    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: barLabels,
            datasets: [
                { label: 'PjBL', data: barPjBL, backgroundColor: '#ffc107' },
                { label: 'CBM', data: barCBM, backgroundColor: '#0dcaf0' }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
    });
});
</script>
@endsection