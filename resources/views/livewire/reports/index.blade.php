<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\Unit;
use App\Models\Expense;
use Carbon\Carbon;

new #[Layout('backend.layouts.app')] class extends Component {
    public $units;
    public $selectedUnit = '';
    public $selectedYear = '';
    public $selectedMonth = '';
    public $years = [];

    public function mount()
    {
        $this->units = Unit::orderBy('name')->get();

        $currentYear = date('Y');
        for ($i = 0; $i < 5; $i++) {
            $this->years[] = $currentYear - $i;
        }

        $this->selectedYear = $currentYear;
    }

    public function with()
    {
        $query = Expense::with(['budget.unit'])->orderBy('date', 'desc');

        if ($this->selectedUnit) {
            $query->whereHas('budget', function ($q) {
                $q->where('unit_id', $this->selectedUnit);
            });
        }

        if ($this->selectedYear) {
            $query->whereYear('date', $this->selectedYear);
        }

        if ($this->selectedMonth) {
            $query->whereMonth('date', $this->selectedMonth);
        }

        return [
            'expenses' => $query->get(),
            'totalAmount' => $query->sum('amount')
        ];
    }
}; ?>

<div class="space-y-6">
    <!-- Header Page & Add Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Laporan Rekapitulasi</h2>
            <p class="text-slate-500 mt-1 text-sm">Filter dan unduh data transaksi belanja (Realisasi Anggaran) dalam
                bentuk PDF atau Excel.</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div
        class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm transition-colors">
        <h3 class="font-bold text-sm text-slate-700 dark:text-slate-300 mb-4 uppercase tracking-wider">Filter Data</h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Unit Alokasi</label>
                <select wire:model.live="selectedUnit"
                    class="w-full text-sm rounded-lg border-slate-200 focus:border-primary focus:ring-primary dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                    <option value="">Semua Unit</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Tahun Pengeluaran</label>
                <select wire:model.live="selectedYear"
                    class="w-full text-sm rounded-lg border-slate-200 focus:border-primary focus:ring-primary dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Bulan Pengeluaran</label>
                <select wire:model.live="selectedMonth"
                    class="w-full text-sm rounded-lg border-slate-200 focus:border-primary focus:ring-primary dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                    <option value="">Seluruh Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                    @endfor
                </select>
            </div>

            <div class="flex items-end gap-2">
                <!-- Export PDF Button -->
                <a href="{{ route('reports.export', ['type' => 'pdf', 'unit_id' => $selectedUnit, 'year' => $selectedYear, 'month' => $selectedMonth]) }}"
                    target="_blank"
                    class="flex-1 flex justify-center items-center gap-2 px-4 py-2 bg-rose-500 text-white text-sm font-medium rounded-lg hover:bg-rose-600 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                    Cetak PDF
                </a>

                <!-- Export Excel Button -->
                <a href="{{ route('reports.export', ['type' => 'excel', 'unit_id' => $selectedUnit, 'year' => $selectedYear, 'month' => $selectedMonth]) }}"
                    class="flex-1 flex justify-center items-center gap-2 px-4 py-2 bg-emerald-500 text-white text-sm font-medium rounded-lg hover:bg-emerald-600 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">grid_on</span>
                    Export Excel
                </a>
            </div>
        </div>
    </div>

    <!-- Data Preview Table -->
    <div
        class="bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div
            class="p-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 flex justify-between items-center">
            <span class="text-sm font-bold text-slate-700 dark:text-slate-200">Pratinjau Data Laporan</span>
            <span class="text-xs font-bold px-2.5 py-1 bg-primary/10 text-primary rounded-full">Total: Rp
                {{ number_format($totalAmount, 0, ',', '.') }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th
                            class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400">
                            Tanggal</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400">
                            Unit / Kendaraan</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400">
                            Deskripsi Transaksi</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400 text-right">
                            Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($expenses as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300 font-mono">
                                {{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-slate-900 dark:text-slate-100">
                                    {{ $item->budget->unit->name }}</p>
                                <p class="text-[11px] text-slate-500 uppercase tracking-widest">
                                    {{ $item->budget->unit->plate_number }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700 dark:text-slate-200">
                                {{ $item->description }}
                            </td>
                            <td
                                class="px-6 py-4 text-sm font-semibold text-right tabular-nums text-slate-700 dark:text-slate-200">
                                Rp {{ number_format($item->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <span
                                    class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 mb-2">find_in_page</span>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Tidak ada transaksi
                                    ditemukan berdasarkan filter di atas.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>