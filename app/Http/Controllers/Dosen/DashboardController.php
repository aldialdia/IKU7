<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $dosenId = Auth::id();
        $selectedSemester = $request->input('semester');

        // --- 1. QUERY DASAR (Scope Dosen) ---
        $matakuliahQuery = MataKuliah::where('user_id', $dosenId);

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

        // --- 4. BAR CHART (PER SEMESTER) ---
        // Karena dosen hanya mengajar beberapa matkul, kita kelompokkan per semester (Tahun Akademik)
        $barChartQuery = MataKuliah::where('user_id', $dosenId)
            ->select('Semester_mk')
            ->addSelect(DB::raw('COUNT(DISTINCT CASE WHEN Metode = "PjBL" THEN Kode_mk END) as count_pjbl'))
            ->addSelect(DB::raw('COUNT(DISTINCT CASE WHEN Metode = "CBM" THEN Kode_mk END) as count_cbm'))
            ->addSelect(DB::raw('COUNT(DISTINCT CASE WHEN Metode = "Biasa" THEN Kode_mk END) as count_biasa'));

        if ($selectedSemester) {
            $barChartQuery->where('Semester_mk', $selectedSemester);
        }

        $barChartData = $barChartQuery->groupBy('Semester_mk')
                                      ->orderBy('Semester_mk')
                                      ->get();

        // Kita tidak perlu lagi membuat variabel $barChart manual di sini
        // karena View sekarang menggunakan $barChartData langsung di JavaScript.

        return view('dosen.dashboard.index', [
            'kpi' => $kpi,
            'pieChartData' => $pieChartData,
            
            // --- INI YANG SEBELUMNYA HILANG ---
            'barChartData' => $barChartData, 
            // ----------------------------------
            
            'old_input' => $request->all()
        ]);
    }
}