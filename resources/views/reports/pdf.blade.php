<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Laporan Realisasi Anggaran Kendaraan Dinas</title>
  <style>
    body {
      font-family: 'Helvetica', 'Arial', sans-serif;
      font-size: 10pt;
      color: #333;
    }

    .header {
      text-align: center;
      margin-bottom: 20px;
      border-bottom: 2px solid #2c3e50;
      padding-bottom: 10px;
    }

    .header h2 {
      margin: 0;
      color: #2c3e50;
      text-transform: uppercase;
      font-size: 16pt;
    }

    .header p {
      margin: 5px 0 0;
      color: #7f8c8d;
      font-size: 11pt;
    }

    .meta-info {
      width: 100%;
      margin-bottom: 20px;
    }

    .meta-info td {
      padding: 4px;
      font-size: 10pt;
    }

    .meta-info td:first-child {
      width: 120px;
      font-weight: bold;
    }

    table.data-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    table.data-table th,
    table.data-table td {
      border: 1px solid #bdc3c7;
      padding: 8px;
    }

    table.data-table th {
      background-color: #f4f6f7;
      text-align: left;
      font-size: 9pt;
      text-transform: uppercase;
    }

    table.data-table td {
      font-size: 10pt;
    }

    .text-right {
      text-align: right;
    }

    .text-center {
      text-align: center;
    }

    .footer {
      margin-top: 30px;
      font-size: 8pt;
      text-align: center;
      color: #95a5a6;
      border-top: 1px solid #ecf0f1;
      padding-top: 10px;
    }

    .total-row {
      font-weight: bold;
      background-color: #ecf0f1;
    }
  </style>
</head>

<body>

  <div class="header">
    <h2>{{ strtoupper($report_title) }}</h2>
    <p>{{ strtoupper($app_name) }}</p>
  </div>

  <table class="meta-info">
    <tr>
      <td>Unit Pelaksana</td>
      <td>: {{ $unit_name }}</td>
      <td class="text-right" style="font-weight: bold;">Tanggal Cetak</td>
      <td class="text-right">: {{ $print_date }}</td>
    </tr>
    <tr>
      <td>Periode Filter</td>
      <td>: {{ $period }}</td>
      <td colspan="2"></td>
    </tr>
  </table>

  <table class="data-table">
    <thead>
      <tr>
        <th width="5%" class="text-center">No</th>
        <th width="15%">Tanggal</th>
        <th width="20%">Unit (Plat)</th>
        <th width="40%">Deskripsi Transaksi Belanja</th>
        <th width="20%" class="text-right">Nominal (Rp)</th>
      </tr>
    </thead>
    <tbody>
      @forelse($expenses as $index => $item)
        <tr>
          <td class="text-center">{{ $index + 1 }}</td>
          <td>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
          <td>
            <strong>{{ $item->budget->unit->name }}</strong><br>
            <span style="font-size: 8pt; color: #7f8c8d;">{{ $item->budget->unit->plate_number }}</span>
          </td>
          <td>{{ $item->description }}</td>
          <td class="text-right">{{ number_format($item->amount, 0, ',', '.') }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center" style="padding: 20px;">Tidak ada data transaksi pada periode ini.</td>
        </tr>
      @endforelse
      @if(count($expenses) > 0)
        <tr class="total-row">
          <td colspan="4" class="text-right" style="padding: 10px;">TOTAL REALISASI BELANJA :</td>
          <td class="text-right" style="padding: 10px;">{{ number_format($totalAmount, 0, ',', '.') }}</td>
        </tr>
      @endif
    </tbody>
  </table>

  <div class="footer">
    Dicetak otomatis oleh {{ $app_name }} &copy; {{ date('Y') }}
  </div>

</body>

</html>