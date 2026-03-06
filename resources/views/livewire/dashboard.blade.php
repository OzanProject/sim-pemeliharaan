<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\Budget;
use App\Models\Expense;
use Carbon\Carbon;

new #[Layout('backend.layouts.app')] class extends Component {
    public $currentYear;
    public $totalAnggaran = 0;
    public $realisasiBelanja = 0;
    public $sisaSaldo = 0;
    public $persentaseRealisasi = 0;
    public $persentaseSisa = 0;

    // Untuk tabel dan grafik
    public $unitAnggaran = [];
    public $trendBulanan = [];

    public function mount()
    {
        $this->currentYear = date('Y');

        // 1. Hitung Total Anggaran Tahun Ini
        $this->totalAnggaran = Budget::where('year', $this->currentYear)->sum('total_amount');

        // 2. Hitung Realisasi Belanja Tahun Ini (join ke budget untuk filter tahun)
        $this->realisasiBelanja = Expense::whereHas('budget', function ($q) {
            $q->where('year', $this->currentYear);
        })->sum('amount');

        // 3. Sisa Saldo
        $this->sisaSaldo = $this->totalAnggaran - $this->realisasiBelanja;

        // 4. Hitung Persentase
        if ($this->totalAnggaran > 0) {
            $this->persentaseRealisasi = round(($this->realisasiBelanja / $this->totalAnggaran) * 100, 1);
            $this->persentaseSisa = round(($this->sisaSaldo / $this->totalAnggaran) * 100, 1);
        }

        // 5. Data Tabel Anggaran Per Unit
        $budgets = Budget::with(['unit', 'expenses'])->where('year', $this->currentYear)->get();

        $this->unitAnggaran = $budgets->map(function ($budget) {
            $realisasi = $budget->expenses->sum('amount');
            $sisa = $budget->total_amount - $realisasi;
            $progress = $budget->total_amount > 0 ? round(($realisasi / $budget->total_amount) * 100) : 0;
            return [
                'name' => $budget->unit->name,
                'total_amount' => $budget->total_amount,
                'realisasi' => $realisasi,
                'sisa' => $sisa,
                'progress' => $progress
            ];
        })->sortByDesc('progress')->take(5)->toArray();

        // 6. Data Tren Bulanan (6 bulan terakhir)
        $labels = [];
        $dataRealisasi = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $labels[] = $monthDate->translatedFormat('M Y');

            $sum = Expense::whereYear('date', $monthDate->year)
                ->whereMonth('date', $monthDate->month)
                ->sum('amount');

            $dataRealisasi[] = $sum;
        }

        $this->trendBulanan = [
            'labels' => $labels,
            'data' => $dataRealisasi
        ];
    }
}; ?>

<div>
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Dashboard Anggaran</h2>
        <p class="text-slate-500 mt-1">Ringkasan pengelolaan anggaran unit kerja TA {{ $currentYear }}.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Anggaran -->
        <div
            class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm transition-colors">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-primary/10 dark:bg-primary/20 text-primary rounded-lg">
                    <span class="material-symbols-outlined">account_balance_wallet</span>
                </div>
                <span
                    class="text-xs font-semibold px-2 py-1 bg-slate-100 dark:bg-slate-800 rounded text-slate-600 dark:text-slate-400">
                    TA {{ $currentYear }}
                </span>
            </div>
            <p class="text-slate-500 text-sm font-medium">Total Pagu Anggaran</p>
            <h3 class="text-2xl font-bold mt-1 text-slate-900 dark:text-slate-100">
                Rp {{ number_format($totalAnggaran, 0, ',', '.') }}
            </h3>
            <div class="mt-4 flex items-center text-slate-400 text-xs gap-1">
                <span class="material-symbols-outlined text-[16px]">history</span>
                Diupdate secara real-time
            </div>
        </div>

        <!-- Realisasi -->
        <div
            class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm transition-colors">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-amber-500/10 text-amber-600 rounded-lg">
                    <span class="material-symbols-outlined">shopping_bag</span>
                </div>
                <span
                    class="px-2 py-1 text-xs font-semibold rounded bg-amber-100 dark:bg-amber-900/20 text-amber-700 dark:text-amber-500">
                    {{ $persentaseRealisasi }}% Terpakai
                </span>
            </div>
            <p class="text-slate-500 text-sm font-medium">Realisasi Belanja</p>
            <h3 class="text-2xl font-bold mt-1 text-slate-900 dark:text-slate-100">
                Rp {{ number_format($realisasiBelanja, 0, ',', '.') }}
            </h3>
            <div class="mt-4 w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                <div class="bg-amber-500 h-full" style="width: {{ $persentaseRealisasi }}%"></div>
            </div>
        </div>

        <!-- Sisa Saldo -->
        <div
            class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm transition-colors">
            <div class="flex justify-between items-start mb-4">
                <div class="text-emerald-500 dark:text-emerald-400 p-3 bg-emerald-500/10 rounded-lg">
                    <span class="material-symbols-outlined">trending_up</span>
                </div>
                <span
                    class="px-2 py-1 text-xs font-semibold rounded text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/20">
                    {{ $persentaseSisa }}% Sisa
                </span>
            </div>
            <p class="text-slate-500 text-sm font-medium">Sisa Saldo</p>
            <h3 class="text-2xl font-bold mt-1 text-emerald-500 border-none dark:text-emerald-400">
                Rp {{ number_format($sisaSaldo, 0, ',', '.') }}
            </h3>
            <div class="mt-4 flex items-center text-emerald-500 dark:text-emerald-400 text-xs gap-1">
                <span class="material-symbols-outlined text-[16px]">verified</span>
                {{ $sisaSaldo >= 0 ? 'Anggaran dalam batas aman' : 'Anggaran defisit' }}
            </div>
        </div>
    </div>

    <!-- Charts & Table Section -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Table Column -->
        <div
            class="xl:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm transition-colors">
            <div class="pb-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                <h3 class="font-bold text-lg text-slate-900 dark:text-slate-100">Top 5 Realisasi Per Unit</h3>
                <a href="{{ route('budgets.index') }}" class="text-sm font-medium text-primary hover:underline">Lihat
                    Semua</a>
            </div>
            <div class="overflow-x-auto mt-4">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400 rounded-tl-lg">
                                No</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400">
                                Nama Unit</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-right bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400">
                                Total Anggaran</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-right bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400">
                                Realisasi</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-right bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400">
                                Sisa</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400 rounded-tr-lg">
                                Progress (%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($unitAnggaran as $index => $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-300">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-slate-300">
                                    {{ $item['name'] }}</td>
                                <td class="px-6 py-4 text-sm text-right tabular-nums text-slate-600 dark:text-slate-300">
                                    {{ number_format($item['total_amount'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-right tabular-nums text-slate-600 dark:text-slate-300">
                                    {{ number_format($item['realisasi'], 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-6 py-4 text-sm text-right tabular-nums font-medium text-emerald-500 dark:text-emerald-400">
                                    {{ number_format($item['sisa'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 min-w-[120px]">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                            <div class="bg-primary h-full" style="width: {{ $item['progress'] }}%"></div>
                                        </div>
                                        <span
                                            class="text-xs font-bold w-8 text-slate-600 dark:text-slate-300">{{ $item['progress'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500">Belum ada data anggaran
                                    tahun ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Side/Chart Column -->
        <div
            class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm transition-colors flex flex-col">
            <div class="mb-6">
                <h3 class="font-bold text-lg mb-1 text-slate-900 dark:text-slate-100">Tren Pengeluaran</h3>
                <p class="text-xs text-slate-500">Penggunaan anggaran 6 bulan terakhir</p>
            </div>

            <!-- Chart Container -->
            <div class="flex-1 w-full min-h-[200px] mt-4 relative">
                <canvas id="trendChart"></canvas>
            </div>
            
            <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 text-center">
                <p class="text-xs text-slate-500 italic">Visualisasi ditarik dari data Realisasi Belanja secara real-time.</p>
            </div>
        </div>
    </div>

    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            const ctx = document.getElementById('trendChart').getContext('2d');
            
            const labels = @json($trendBulanan['labels']);
            const data = @json($trendBulanan['data']);
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Realisasi Belanja (Rp)',
                        data: data,
                        backgroundColor: '#818df8', // Tailwind Primary color
                        borderRadius: 4,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let value = context.raw;
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return (value / 1000000) + ' Jt';
                                    }
                                    return value;
                                }
                            },
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
</div>