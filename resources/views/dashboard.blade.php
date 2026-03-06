<x-app-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Dashboard Anggaran</h2>
        <p class="text-slate-500 mt-1">Ringkasan pengelolaan anggaran unit kerja periode berjalan.</p>
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
                    class="text-xs font-semibold px-2 py-1 bg-slate-100 dark:bg-slate-800 rounded text-slate-600 dark:text-slate-400">TA
                    {{ date('Y') }}</span>
            </div>
            <p class="text-slate-500 text-sm font-medium">Total Anggaran</p>
            <h3 class="text-2xl font-bold mt-1 text-slate-900 dark:text-slate-100">Rp 25.000.000.000</h3>
            <div class="mt-4 flex items-center text-slate-400 text-xs gap-1">
                <span class="material-symbols-outlined text-[16px]">history</span>
                Terakhir diupdate 2 jam yang lalu
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
                    class="px-2 py-1 text-xs font-semibold rounded bg-amber-100 dark:bg-amber-900/20 text-amber-700 dark:text-amber-500">73.8%
                    Terpakai</span>
            </div>
            <p class="text-slate-500 text-sm font-medium">Realisasi Belanja</p>
            <h3 class="text-2xl font-bold mt-1 text-slate-900 dark:text-slate-100">Rp 18.450.000.000</h3>
            <div class="mt-4 w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                <div class="bg-amber-500 h-full w-[73.8%]"></div>
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
                    class="px-2 py-1 text-xs font-semibold rounded text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/20">+26.2%
                    Efisiensi</span>
            </div>
            <p class="text-slate-500 text-sm font-medium">Sisa Saldo</p>
            <h3 class="text-2xl font-bold mt-1 text-emerald-500 border-none dark:text-emerald-400">Rp 6.550.000.000</h3>
            <div class="mt-4 flex items-center text-emerald-500 dark:text-emerald-400 text-xs gap-1">
                <span class="material-symbols-outlined text-[16px]">verified</span>
                Anggaran dalam batas aman
            </div>
        </div>
    </div>

    <!-- Charts & Table Section -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Table Column -->
        <div
            class="xl:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm transition-colors">
            <div class="pb-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                <h3 class="font-bold text-lg text-slate-900 dark:text-slate-100">Data Anggaran Per Unit</h3>
                <button class="text-sm font-medium text-primary hover:underline">Lihat Semua</button>
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
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-300">1</td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-slate-300">Bagian Umum
                            </td>
                            <td class="px-6 py-4 text-sm text-right tabular-nums text-slate-600 dark:text-slate-300">
                                5.000.000.000</td>
                            <td class="px-6 py-4 text-sm text-right tabular-nums text-slate-600 dark:text-slate-300">
                                4.000.000.000</td>
                            <td
                                class="px-6 py-4 text-sm text-right tabular-nums font-medium text-emerald-500 dark:text-emerald-400">
                                1.000.000.000</td>
                            <td class="px-6 py-4 min-w-[120px]">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                        <div class="bg-primary h-full" style="width: 80%"></div>
                                    </div>
                                    <span class="text-xs font-bold w-8 text-slate-600 dark:text-slate-300">80%</span>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-300">2</td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-slate-300">Bagian
                                Keuangan</td>
                            <td class="px-6 py-4 text-sm text-right tabular-nums text-slate-600 dark:text-slate-300">
                                3.000.000.000</td>
                            <td class="px-6 py-4 text-sm text-right tabular-nums text-slate-600 dark:text-slate-300">
                                2.000.000.000</td>
                            <td
                                class="px-6 py-4 text-sm text-right tabular-nums font-medium text-emerald-500 dark:text-emerald-400">
                                1.000.000.000</td>
                            <td class="px-6 py-4 min-w-[120px]">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                        <div class="bg-primary h-full" style="width: 66%"></div>
                                    </div>
                                    <span class="text-xs font-bold w-8 text-slate-600 dark:text-slate-300">66%</span>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-300">3</td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-slate-300">Bagian SDM
                            </td>
                            <td class="px-6 py-4 text-sm text-right tabular-nums text-slate-600 dark:text-slate-300">
                                2.500.000.000</td>
                            <td class="px-6 py-4 text-sm text-right tabular-nums text-slate-600 dark:text-slate-300">
                                1.000.000.000</td>
                            <td
                                class="px-6 py-4 text-sm text-right tabular-nums font-medium text-emerald-500 dark:text-emerald-400">
                                1.500.000.000</td>
                            <td class="px-6 py-4 min-w-[120px]">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                        <div class="bg-primary h-full" style="width: 40%"></div>
                                    </div>
                                    <span class="text-xs font-bold w-8 text-slate-600 dark:text-slate-300">40%</span>
                                </div>
                            </td>
                        </tr>
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

            <!-- Pseudo Bar Chart -->
            <div class="flex-1 flex flex-col justify-end gap-2 min-h-[200px]">
                <div class="flex items-end justify-between h-32 gap-2">
                    <div class="w-full bg-primary/20 dark:bg-primary/30 hover:bg-primary transition-colors rounded-t group relative"
                        style="height: 38%">
                        <div
                            class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] px-2 py-1 rounded hidden group-hover:block whitespace-nowrap">
                            Jan: 1.2M</div>
                    </div>
                    <div class="w-full bg-primary/20 dark:bg-primary/30 hover:bg-primary transition-colors rounded-t group relative"
                        style="height: 58%">
                        <div
                            class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] px-2 py-1 rounded hidden group-hover:block whitespace-nowrap">
                            Feb: 1.8M</div>
                    </div>
                    <div class="w-full bg-primary/20 dark:bg-primary/30 hover:bg-primary transition-colors rounded-t group relative"
                        style="height: 35%">
                        <div
                            class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] px-2 py-1 rounded hidden group-hover:block whitespace-nowrap">
                            Mar: 1.1M</div>
                    </div>
                    <div class="w-full bg-primary/20 dark:bg-primary/30 hover:bg-primary transition-colors rounded-t group relative"
                        style="height: 77%">
                        <div
                            class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] px-2 py-1 rounded hidden group-hover:block whitespace-nowrap">
                            Apr: 2.4M</div>
                    </div>
                    <div class="w-full bg-primary/20 dark:bg-primary/30 hover:bg-primary transition-colors rounded-t group relative"
                        style="height: 64%">
                        <div
                            class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] px-2 py-1 rounded hidden group-hover:block whitespace-nowrap">
                            Mei: 2.0M</div>
                    </div>
                    <div class="w-full bg-primary/20 dark:bg-primary/30 hover:bg-primary transition-colors rounded-t group relative"
                        style="height: 100%">
                        <div
                            class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] px-2 py-1 rounded hidden group-hover:block whitespace-nowrap">
                            Jun: 3.1M</div>
                    </div>
                </div>
                <div class="flex justify-between text-[10px] text-slate-400 font-bold uppercase mt-2">
                    <span>Jan</span>
                    <span>Feb</span>
                    <span>Mar</span>
                    <span>Apr</span>
                    <span>Mei</span>
                    <span>Jun</span>
                </div>
            </div>

            <!-- Top Cards -->
            <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">Unit Paling Aktif</span>
                    <span
                        class="px-2 py-1 text-[10px] font-bold uppercase rounded bg-amber-100 dark:bg-amber-900/20 text-amber-700 dark:text-amber-500">Bulan
                        Ini</span>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="size-8 rounded bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-bold text-xs text-slate-500">
                            BU</div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-slate-900 dark:text-slate-100">Bagian Umum</p>
                            <p class="text-[10px] text-slate-500">12 Transaksi Belanja</p>
                        </div>
                        <span class="text-xs font-bold text-slate-900 dark:text-slate-100">Rp 4.2M</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div
                            class="size-8 rounded bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-bold text-xs text-slate-500">
                            BK</div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-slate-900 dark:text-slate-100">Bagian Keuangan</p>
                            <p class="text-[10px] text-slate-500">8 Transaksi Belanja</p>
                        </div>
                        <span class="text-xs font-bold text-slate-900 dark:text-slate-100">Rp 2.1M</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>