<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class ExpensesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
  protected $expenses;
  protected $appName;
  protected $reportTitle;

  public function __construct($expenses, $appName = 'SIM Kendaraan', $reportTitle = 'LAPORAN REALISASI ANGGARAN KENDARAAN DINAS')
  {
    $this->expenses = $expenses;
    $this->appName = $appName;
    $this->reportTitle = $reportTitle;
  }

  public function collection()
  {
    $total = $this->expenses->sum('amount');
    $this->expenses->push((object) [
      'is_total' => true,
      'amount' => $total
    ]);
    return $this->expenses;
  }

  /**
   * @var Expense $expense
   */
  public function map($expense): array
  {
    if (isset($expense->is_total) && $expense->is_total) {
      return [
        '',
        '',
        '',
        'TOTAL REALISASI:',
        $expense->amount,
      ];
    }

    return [
      Carbon::parse($expense->date)->format('d/m/Y'),
      $expense->budget->unit->name,
      $expense->budget->unit->plate_number,
      $expense->description,
      $expense->amount,
    ];
  }

  public function headings(): array
  {
    return [
      [strtoupper($this->reportTitle) . ' - ' . strtoupper($this->appName)],
      ['Tanggal Cetak: ' . Carbon::now()->translatedFormat('d F Y H:i:s')],
      [''], // Empty row
      [
        'Tanggal',
        'Unit Kendaraan',
        'Plat Nomor',
        'Deskripsi Transaksi',
        'Nominal (Rp)',
      ]
    ];
  }

  public function styles(Worksheet $sheet)
  {
    $lastRow = $this->expenses->count() + 4; // +4 Karena ada 4 baris header tambahan

    // Merge Cells untuk Judul
    $sheet->mergeCells('A1:E1');
    $sheet->mergeCells('A2:E2');

    return [
      1 => ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
      2 => ['font' => ['italic' => true], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
      4 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF4F46E5']]],
      $lastRow => ['font' => ['bold' => true]],
    ];
  }
}
