<?php

namespace App\Http\Controllers\Rektorat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MataKuliah;
use App\Models\Fakultas;
use App\Models\Departemen;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // --- 1. AMBIL FILTER ---
        $selectedSemester = $request->input('semester');
        // $selectedTahun = $request->input('tahun'); // Untuk nanti

        // --- 2. QUERY DASAR UNTUK KPI & PIE CHART ---
        $matakuliahQuery = MataKuliah::query();

        // Terapkan filter jika ada
        if ($selectedSemester) {
            $matakuliahQuery->where('Semester_mk', $selectedSemester);
        }
        // if ($selectedTahun) {
        //     $matakuliahQuery->where('tahun_akademik', $selectedTahun); // Untuk nanti
        // }

        // Clone query untuk perhitungan terpisah
        $queryPjBL = (clone $matakuliahQuery)->where('Metode', 'PjBL');
        $queryCBM = (clone $matakuliahQuery)->where('Metode', 'CBM');
        $queryBiasa = (clone $matakuliahQuery)->where('Metode', 'Biasa');

        // --- 3. HITUNG KPI CARD ---
        $kpi = [
            'totalMatkul' => $matakuliahQuery->count(),
            'totalPjBL' => $queryPjBL->count(),
            'totalCBM' => $queryCBM->count(),
            'totalBiasa' => $queryBiasa->count(),
        ];

        // --- 4. SIAPKAN DATA PIE CHART ---
        $pieChartData = [
            'labels' => ['Project Based Learning', 'Case Based Method', 'Biasa'],
            'data' => [
                $kpi['totalPjBL'],
                $kpi['totalCBM'],
                $kpi['totalBiasa'],
            ]
        ];

        // --- 5. SIAPKAN DATA BAR CHART (PER FAKULTAS) ---
        $barChartQuery = Fakultas::query()
            ->select('fakultas.id_fakultas', 'fakultas.Nama_fakultas')
            // Hitung PjBL
            ->addSelect(DB::raw('COUNT(DISTINCT CASE WHEN mata_kuliah.Metode = "PjBL" THEN mata_kuliah.Kode_mk END) as count_pjbl'))
            // Hitung CBM
            ->addSelect(DB::raw('COUNT(DISTINCT CASE WHEN mata_kuliah.Metode = "CBM" THEN mata_kuliah.Kode_mk END) as count_cbm'))
            // Gabungkan tabel
            ->leftJoin('departemen', 'fakultas.id_fakultas', '=', 'departemen.id_fakultas')
            ->leftJoin('mata_kuliah', 'departemen.id_departemen', '=', 'mata_kuliah.id_departemen');

        // Terapkan filter ke query bar chart juga
        if ($selectedSemester) {
            $barChartQuery->where('mata_kuliah.Semester_mk', $selectedSemester);
        }
        // if ($selectedTahun) {
        //     $barChartQuery->where('mata_kuliah.tahun_akademik', $selectedTahun); // Untuk nanti
        // }

        $barChartData = $barChartQuery->groupBy('fakultas.id_fakultas', 'fakultas.Nama_fakultas')
                                      ->orderBy('fakultas.Nama_fakultas')
                                      ->get();

        // Pisahkan data untuk Chart.js
        $barChart = [
            'labels' => $barChartData->pluck('Nama_fakultas'),
            'data_pjbl' => $barChartData->pluck('count_pjbl'),
            'data_cbm' => $barChartData->pluck('count_cbm'),
        ];

        return view('rektorat.dashboard.index', [
            'kpi' => $kpi,
            'pieChartData' => $pieChartData,
            'barChartData' => $barChartData,
            'old_input' => $request->all()
        ]);
    }

    public function showFakultasDetail(Request $request, Fakultas $fakultas)
    {
        // 1. Ambil filter
        $selectedSemester = $request->input('semester');
        
        // 2. Query data
        $departemenQuery = Departemen::query()
            ->select('departemen.id_departemen', 'departemen.Nama_departemen')
            ->addSelect(DB::raw('COUNT(DISTINCT CASE WHEN mata_kuliah.Metode = "PjBL" THEN mata_kuliah.Kode_mk END) as count_pjbl'))
            ->addSelect(DB::raw('COUNT(DISTINCT CASE WHEN mata_kuliah.Metode = "CBM" THEN mata_kuliah.Kode_mk END) as count_cbm'))
            ->addSelect(DB::raw('COUNT(DISTINCT CASE WHEN mata_kuliah.Metode = "Biasa" THEN mata_kuliah.Kode_mk END) as count_biasa'))
            ->leftJoin('mata_kuliah', 'departemen.id_departemen', '=', 'mata_kuliah.id_departemen')
            ->where('departemen.id_fakultas', $fakultas->id_fakultas); // Filter untuk fakultas ini

        // 3. Terapkan filter semester jika ada
        if ($selectedSemester) {
            $departemenQuery->where('mata_kuliah.Semester_mk', $selectedSemester);
        }
        
        $departemenData = $departemenQuery->groupBy('departemen.id_departemen', 'departemen.Nama_departemen')
                                          ->orderBy('departemen.Nama_departemen')
                                          ->get();

        // 4. Siapkan data untuk Chart.js
        $barChart = [
            'labels' => $departemenData->pluck('Nama_departemen'),
            'data_pjbl' => $departemenData->pluck('count_pjbl'),
            'data_cbm' => $departemenData->pluck('count_cbm'),
            'data_biasa' => $departemenData->pluck('count_biasa'),
        ];
        
        return view('rektorat.dashboard.detail_fakultas', [
            'fakultas' => $fakultas,
            'departemenData' => $departemenData,
            'barChart' => $barChart,
            'old_input' => $request->all()
        ]);
    }

    public function showDepartemenDetail(Request $request, Departemen $departemen)
    {
        // 1. Ambil filter
        $selectedSemester = $request->input('semester');

        // 2. Load relasi fakultas untuk 'breadcrumb'
        $departemen->load('fakultas'); 
        
        // 3. Query dasar: Matkul di departemen ini
        $matakuliahQuery = MataKuliah::where('id_departemen', $departemen->id_departemen);

        // 4. Terapkan filter semester jika ada
        if ($selectedSemester) {
            $matakuliahQuery->where('Semester_mk', $selectedSemester);
        }

        // 5. Ambil data
        $mataKuliahList = $matakuliahQuery->with('dosen')
                                          ->orderBy('verified', 'asc')
                                          ->orderBy('Nama_mk', 'asc')
                                          ->get();
        
        return view('rektorat.dashboard.detail_departemen', [
            'departemen' => $departemen,
            'mataKuliahList' => $mataKuliahList,
            'old_input' => $request->all()
        ]);
    }
}