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
        // Karena dosen hanya mengajar beberapa matkul, kita kelompokkan per semester saja
        $barChartQuery = MataKuliah::where('user_id', $dosenId)
            ->select('Semester_mk')
            ->addSelect(DB::raw('COUNT(DISTINCT CASE WHEN Metode = "PjBL" THEN Kode_mk END) as count_pjbl'))
            ->addSelect(DB::raw('COUNT(DISTINCT CASE WHEN Metode = "CBM" THEN Kode_mk END) as count_cbm'));

        if ($selectedSemester) {
            $barChartQuery->where('Semester_mk', $selectedSemester);
        }

        $barChartData = $barChartQuery->groupBy('Semester_mk')
                                      ->orderBy('Semester_mk')
                                      ->get();

        $barChart = [
            'labels' => $barChartData->map(fn($item) => 'Semester ' . $item->Semester_mk),
            'data_pjbl' => $barChartData->pluck('count_pjbl'),
            'data_cbm' => $barChartData->pluck('count_cbm'),
        ];

        return view('dosen.dashboard.index', [
            'kpi' => $kpi,
            'pieChartData' => $pieChartData,
            'barChart' => $barChart,
            'old_input' => $request->all()
        ]);
    }
}