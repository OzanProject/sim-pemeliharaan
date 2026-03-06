<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\Budget;
use App\Models\Unit;

new #[Layout('backend.layouts.app')] class extends Component {
    public $budgets;
    public $availableUnits;

    // Form Properties
    public $budget_id;
    public $unit_id;
    public $year;
    public $total_amount;

    // State 
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadData();
        $this->year = date('Y');
    }

    public function loadData()
    {
        $this->budgets = Budget::with('unit')->orderBy('year', 'desc')->get();
        $this->availableUnits = Unit::where('status', 'active')->orderBy('name', 'asc')->get();
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function resetForm()
    {
        $this->budget_id = null;
        $this->unit_id = '';
        $this->year = date('Y');
        $this->total_amount = '';
        $this->isEditMode = false;
    }

    public function save()
    {
        $rules = [
            'unit_id' => 'required|exists:units,id',
            'year' => 'required|integer|min:2000|max:2099',
            'total_amount' => 'required|numeric|min:0',
        ];

        $this->validate($rules);

        // Ubah string yang mungkin diketik user dengan format nominal bebas koma
        $cleanAmount = str_replace(['.', ','], '', $this->total_amount);

        Budget::updateOrCreate(
            ['id' => $this->budget_id],
            [
                'unit_id' => $this->unit_id,
                'year' => $this->year,
                'total_amount' => $cleanAmount,
            ]
        );

        session()->flash('message', $this->isEditMode ? 'Data Anggaran berhasil diperbarui.' : 'Data Anggaran berhasil ditambahkan.');

        $this->closeModal();
        $this->loadData();
    }

    public function edit($id)
    {
        $budget = Budget::findOrFail($id);
        $this->budget_id = $budget->id;
        $this->unit_id = $budget->unit_id;
        $this->year = $budget->year;
        $this->total_amount = $budget->total_amount;

        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        Budget::findOrFail($id)->delete();
        session()->flash('message', 'Data Anggaran berhasil dihapus.');
        $this->loadData();
    }
}; ?>

<div class="space-y-6">
        <!-- Header Page & Add Button -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Data Anggaran Unit</h2>
                <p class="text-slate-500 mt-1 text-sm">Kelola alokasi pagu anggaran tahunan untuk setiap unit pelaksana.
                </p>
            </div>
            <button wire:click="openModal"
                class="flex items-center gap-2 px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-indigo-600 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Input Anggaran
            </button>
        </div>

        <!-- Flash Message -->
        @if (session()->has('message'))
            <div
                class="p-4 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-xl border border-emerald-100 dark:border-emerald-800/30 flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                <span class="text-sm font-medium">{{ session('message') }}</span>
            </div>
        @endif

        <!-- Data Table -->
        <div
            class="bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400">
                                No</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400">
                                Nama Unit Pelaksana</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400 text-center">
                                Tahun Anggaran</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400 text-right">
                                Pagu Total</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400 text-right">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($budgets as $index => $budget)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-300">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-slate-900 dark:text-slate-100">
                                        {{ $budget->unit->name }}</p>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                        {{ $budget->unit->plate_number ?? 'Tanpa plat nomor' }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-center font-semibold text-primary dark:text-indigo-400">
                                    {{ $budget->year }}
                                </td>
                                <td
                                    class="px-6 py-4 text-sm text-right font-medium text-slate-700 dark:text-slate-200 tabular-nums">
                                    Rp {{ number_format($budget->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="edit({{ $budget->id }})"
                                            class="p-2 text-slate-400 hover:text-primary hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-lg transition-colors"
                                            title="Edit Data">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </button>
                                        <button wire:click="delete({{ $budget->id }})"
                                            wire:confirm="Yakin menghapus plafon anggaran ini? Semua transaksi belanja terkait unit & tahun ini ikut terhapus!"
                                            class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors"
                                            title="Hapus Data">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <span
                                        class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 mb-2">payments</span>
                                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada pagu
                                        anggaran yang didata.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Form CRUD -->
        @if($isModalOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="closeModal">
                </div>

                <!-- Modal Panel -->
                <div
                    class="relative bg-white dark:bg-slate-900 rounded-xl shadow-xl w-full max-w-lg overflow-hidden transition-all border border-slate-100 dark:border-slate-800">
                    <div
                        class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/20">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                            {{ $isEditMode ? 'Edit Alokasi Anggaran' : 'Input Anggaran Unit' }}
                        </h3>
                        <button wire:click="closeModal"
                            class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    <form wire:submit="save">
                        <div class="p-6 space-y-5">

                            <!-- Pilihan Unit -->
                            <div>
                                <label for="unit_id"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Unit Kendaraan
                                    / Bagian <span class="text-red-500">*</span></label>
                                <select id="unit_id" wire:model="unit_id"
                                    class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-primary focus:border-primary sm:text-sm">
                                    <option value="">-- Pilih Unit Aktif --</option>
                                    @foreach($availableUnits as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->plate_number ?? 'Tanpa Plat' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <!-- Input Tahun -->
                                <div>
                                    <label for="year"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tahun
                                        Anggaran <span class="text-red-500">*</span></label>
                                    <input type="number" id="year" wire:model="year" min="2000" max="2099"
                                        class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-primary focus:border-primary sm:text-sm">
                                    @error('year') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Input Nominal Pagu -->
                                <div>
                                    <label for="total_amount"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Total Pagu
                                        (Rp) <span class="text-red-500">*</span></label>
                                    <input type="number" id="total_amount" wire:model="total_amount"
                                        class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-primary focus:border-primary sm:text-sm"
                                        placeholder="Contoh: 5000000">
                                    @error('total_amount') <span
                                    class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                        </div>

                        <div
                            class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3 rounded-b-xl">
                            <button type="button" wire:click="closeModal"
                                class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Batal</button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-indigo-600 shadow-sm transition-colors flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">save</span>
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>