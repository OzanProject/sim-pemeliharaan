<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
  public function export(Request $request)
  {
    $type = $request->query('type', 'pdf');

    $query = Expense::with(['budget.unit'])->orderBy('date', 'desc');

    $unit_name = 'Semua Unit';
    $period = 'Semua Waktu';

    if ($request->filled('unit_id')) {
      $query->whereHas('budget', function ($q) use ($request) {
        $q->where('unit_id', $request->unit_id);
      });
      $unit = Unit::find($request->unit_id);
      if ($unit)
        $unit_name = $unit->name;
    }

    if ($request->filled('year')) {
      $query->whereYear('date', $request->year);
      $period = 'Tahun ' . $request->year;

      if ($request->filled('month')) {
        $query->whereMonth('date', $request->month);
        $monthName = Carbon::create()->month($request->month)->translatedFormat('F');
        $period = 'Bulan ' . $monthName . ' Tahun ' . $request->year;
      }
    }

    $expenses = $query->get();
    $totalAmount = $expenses->sum('amount');
    $appName = \App\Models\Setting::where('key', 'app_name')->value('value') ?? 'SIM Kendaraan';
    $reportTitle = \App\Models\Setting::where('key', 'report_title')->value('value') ?? 'LAPORAN REALISASI ANGGARAN KENDARAAN DINAS';

    $data = [
      'expenses' => $expenses,
      'totalAmount' => $totalAmount,
      'unit_name' => $unit_name,
      'period' => $period,
      'app_name' => $appName,
      'report_title' => $reportTitle,
      'print_date' => Carbon::now()->translatedFormat('d F Y H:i:s')
    ];

    if ($type === 'pdf') {
      $pdf = Pdf::loadView('reports.pdf', $data);
      $pdf->setPaper('A4', 'portrait');
      return $pdf->download('Laporan_Realisasi_Kendaraan_' . date('Ymd_His') . '.pdf');
    } elseif ($type === 'excel') {
      return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ExpensesExport($expenses, $appName, $reportTitle), 'Laporan_Realisasi_Kendaraan_' . date('Ymd_His') . '.xlsx');
    }

    return redirect()->back()->with('error', 'Format tidak valid');
  }
}
