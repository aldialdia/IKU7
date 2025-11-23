<?php

namespace App\Http\Controllers\Fakultas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MataKuliah;
use App\Models\Departemen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $fakultasId = Auth::user()->id_fakultas;
        $selectedSemester = $request->input('semester');

        // --- 1. QUERY DASAR (Scope Fakultas) ---
        $matakuliahQuery = MataKuliah::whereHas('departemen', function($q) use ($fakultasId) {
            $q->where('id_fakultas', $fakultasId);
        });

        if ($selectedSemester) {
            $matakuliahQuery->where('Semester_mk', $selectedSemester);
        }

        $queryPjBL = (clone $matakuliahQuery)->where('Metode', 'PjBL');
        $queryCBM = (clone $matakuliahQuery)->where('Metode', 'CBM');
        $queryBiasa = (clone $matakuliahQuery)->where('Metode', 'Biasa');

        // --- 2. KPI CARD ---
        $kpi = [
            'totalMatkul' => $matakuliahQuery->count(),
            'totalPjBL' => $queryPjBL->count(),
            'totalCBM' => $queryCBM->count(),
            'totalBiasa' => $queryBiasa->count(),
        ];

        // --- 3. PIE CHART ---
        $pieChartData = [
            'labels' => ['Project Based Learning', 'Case Based Method', 'Biasa'],
            'data' => [$kpi['totalPjBL'], $kpi['totalCBM'], $kpi['totalBiasa']]
        ];

        // --- 4. BAR CHART & TABEL (PER DEPARTEMEN) ---
        $barChartQuery = Departemen::where('id_fakultas', $fakultasId)
            ->select('departemen.id_departemen', 'departemen.Nama_departemen') // Pastikan ID terpilih
            ->addSelect(DB::raw('COUNT(DISTINCT CASE WHEN mata_kuliah.Metode = "PjBL" THEN mata_kuliah.Kode_mk END) as count_pjbl'))
            ->addSelect(DB::raw('COUNT(DISTINCT CASE WHEN mata_kuliah.Metode = "CBM" THEN mata_kuliah.Kode_mk END) as count_cbm'))
            ->addSelect(DB::raw('COUNT(DISTINCT CASE WHEN mata_kuliah.Metode = "Biasa" THEN mata_kuliah.Kode_mk END) as count_biasa')) // Tambah Biasa
            ->leftJoin('mata_kuliah', 'departemen.id_departemen', '=', 'mata_kuliah.id_departemen');

        if ($selectedSemester) {
            $barChartQuery->where('mata_kuliah.Semester_mk', $selectedSemester);
        }

        // Data lengkap untuk Tabel
        $barChartData = $barChartQuery->groupBy('departemen.id_departemen', 'departemen.Nama_departemen')
                                      ->orderBy('departemen.Nama_departemen')
                                      ->get();

        // Data ringkas untuk Chart.js
        $barChart = [
            'labels' => $barChartData->pluck('Nama_departemen'),
            'data_pjbl' => $barChartData->pluck('count_pjbl'),
            'data_cbm' => $barChartData->pluck('count_cbm'),
        ];

        return view('fakultas.dashboard.index', [
            'kpi' => $kpi,
            'pieChartData' => $pieChartData,
            'barChart' => $barChart,
            'barChartData' => $barChartData, // <-- INI PENTING UNTUK TABEL
            'old_input' => $request->all()
        ]);
    }

    /**
     * === METHOD BARU: DETAIL DEPARTEMEN ===
     */
    public function showDepartemenDetail(Request $request, Departemen $departemen)
    {
        // 1. Security Check: Pastikan departemen ini milik fakultas admin
        if ($departemen->id_fakultas !== Auth::user()->id_fakultas) {
            return redirect()->route('fakultas.dashboard')
                             ->withErrors('Anda tidak berhak mengakses departemen ini.');
        }

        // 2. Filter
        $selectedSemester = $request->input('semester');

        // 3. Query Mata Kuliah
        $matakuliahQuery = MataKuliah::where('id_departemen', $departemen->id_departemen)
                                     ->with('dosen'); // Eager load dosen

        if ($selectedSemester) {
            $matakuliahQuery->where('Semester_mk', $selectedSemester);
        }

        $mataKuliahList = $matakuliahQuery->orderBy('verified', 'asc') // Unverified dulu
                                          ->orderBy('Nama_mk', 'asc')
                                          ->get();

        return view('fakultas.dashboard.detail_departemen', [
            'departemen' => $departemen,
            'mataKuliahList' => $mataKuliahList,
            'old_input' => $request->all()
        ]);
    }
    public function showMatkulDetail(MataKuliah $matakuliah)
    {
        // Security Check: Pastikan matkul ini ada di fakultas si admin
        // Kita cek via relasi: matkul -> departemen -> id_fakultas
        if ($matakuliah->departemen->id_fakultas !== Auth::user()->id_fakultas) {
            return redirect()->route('fakultas.dashboard')
                             ->withErrors('Anda tidak berhak melihat mata kuliah ini.');
        }

        // Load semua relasi
        $matakuliah->load(['departemen.fakultas', 'dosen', 'komponenPenilaian', 'dokumenPendukung']);

        return view('fakultas.dashboard.detail_matkul', [
            'matakuliah' => $matakuliah
        ]);
    }
}