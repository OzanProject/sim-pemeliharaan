<?php

use Livewire\Volt\Component;
use App\Models\Expense;
use App\Models\Budget;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

new #[Layout('backend.layouts.app')] class extends Component {
    use WithFileUploads;

    public $expenses;
    public $availableBudgets;

    // Form Properties
    public $expense_id;
    public $budget_id;
    public $date;
    public $description;
    public $amount;
    public $receipt; // For File Upload
    public $existing_receipt_path;

    // State 
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadData();
        $this->date = date('Y-m-d');
    }

    public function loadData()
    {
        $this->expenses = Expense::with(['budget.unit'])->orderBy('date', 'desc')->get();
        // Load budget yang aktif tahun ini atau semua tahun agar gampang dipilih
        $this->availableBudgets = Budget::with('unit')->orderBy('year', 'desc')->get();
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
        $this->expense_id = null;
        $this->budget_id = '';
        $this->date = date('Y-m-d');
        $this->description = '';
        $this->amount = '';
        $this->receipt = null;
        $this->existing_receipt_path = null;
        $this->isEditMode = false;
    }

    public function save()
    {
        $rules = [
            'budget_id' => 'required|exists:budgets,id',
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'receipt' => 'nullable|image|max:2048', // Mask 2MB Image
        ];

        $this->validate($rules);

        // Ubah string angka nominal
        $cleanAmount = str_replace(['.', ','], '', $this->amount);

        // Handle File Upload
        $path = $this->existing_receipt_path;
        if ($this->receipt) {
            $path = $this->receipt->store('receipts', 'public');
        }

        $savedExpense = Expense::updateOrCreate(
            ['id' => $this->expense_id],
            [
                'budget_id' => $this->budget_id,
                'date' => $this->date,
                'description' => $this->description,
                'amount' => $cleanAmount,
                'receipt_path' => $path,
            ]
        );

        if (!$this->isEditMode) {
            $usersToNotify = \App\Models\User::where('id', '!=', auth()->id())->get();
            if ($usersToNotify->count() > 0) {
                \Illuminate\Support\Facades\Notification::send($usersToNotify, new \App\Notifications\NewExpenseNotification($savedExpense, auth()->user()->name));
            }
        }

        $this->dispatch('swal', title: $this->isEditMode ? 'Data Realisasi Belanja berhasil diperbarui.' : 'Data Realisasi Belanja berhasil dicatat.', icon: 'success');

        $this->closeModal();
        $this->loadData();
    }

    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        $this->expense_id = $expense->id;
        $this->budget_id = $expense->budget_id;
        $this->date = $expense->date;
        $this->description = $expense->description;
        $this->amount = $expense->amount;
        $this->existing_receipt_path = $expense->receipt_path;

        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        Expense::findOrFail($id)->delete();
        $this->dispatch('swal', title: 'Data Transaksi Belanja berhasil dihapus.', icon: 'success');
        $this->loadData();
    }
}; ?>

<div class="space-y-6">
    <!-- Header Page & Add Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Realisasi Belanja</h2>
            <p class="text-slate-500 mt-1 text-sm">Catat pengeluaran operasional dan pemeliharaan kendaraan harian.
            </p>
        </div>
        <button wire:click="openModal"
            class="flex items-center gap-2 px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-indigo-600 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Catat Pengeluaran
        </button>
    </div>

    <!-- Data Table -->
    <div
        class="bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th
                            class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400">
                            Tanggal</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400">
                            Deskripsi / Uraian</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400">
                            Unit & Anggaran</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400 text-right">
                            Nominal Pengeluaran</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400 text-center">
                            Nota</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400 text-right">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($expenses as $expense)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-slate-700 dark:text-slate-300 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-900 dark:text-slate-100">
                                {{ $expense->description }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-primary dark:text-indigo-400">
                                    {{ $expense->budget->unit->name }}
                                </p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">TA.
                                    {{ $expense->budget->year }}
                                </p>
                            </td>
                            <td
                                class="px-6 py-4 text-sm text-right font-medium text-amber-600 dark:text-amber-500 tabular-nums">
                                Rp {{ number_format($expense->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($expense->receipt_path)
                                    <a href="{{ Storage::url($expense->receipt_path) }}" target="_blank"
                                        class="inline-flex items-center justify-center p-1.5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded hover:text-primary transition-colors"
                                        title="Lihat Bukti Foto">
                                        <span class="material-symbols-outlined text-[16px]">receipt_long</span>
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="edit({{ $expense->id }})"
                                        class="p-2 text-slate-400 hover:text-primary hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-lg transition-colors"
                                        title="Edit Data">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <button wire:click="delete({{ $expense->id }})"
                                        wire:confirm="Yakin menghapus catatan transaksi ini?"
                                        class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors"
                                        title="Hapus Data">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <span
                                    class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 mb-2">receipt</span>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada catatan
                                    realisasi belanja.</p>
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
                        {{ $isEditMode ? 'Edit Transaksi Belanja' : 'Input Realisasi / Belanja' }}
                    </h3>
                    <button wire:click="closeModal"
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="p-6 space-y-4">

                        <!-- Pilihan Budget/Unit -->
                        <div>
                            <label for="budget_id"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Sumber porsi
                                anggaran <span class="text-red-500">*</span></label>
                            <select id="budget_id" wire:model="budget_id"
                                class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-primary focus:border-primary sm:text-sm">
                                <option value="">-- Pilih Buku Anggaran Unit --</option>
                                @foreach($availableBudgets as $b)
                                    <option value="{{ $b->id }}">{{ $b->unit->name }} (TA. {{ $b->year }})</option>
                                @endforeach
                            </select>
                            @error('budget_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Input Tanggal -->
                            <div>
                                <label for="date"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tanggal
                                    Pembelian <span class="text-red-500">*</span></label>
                                <input type="date" id="date" wire:model="date"
                                    class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-primary focus:border-primary sm:text-sm">
                                @error('date') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Input Nominal Belanja -->
                            <div>
                                <label for="amount"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Biaya
                                    Pengeluaran (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" id="amount" wire:model="amount"
                                    class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-primary focus:border-primary sm:text-sm"
                                    placeholder="1000000">
                                @error('amount') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Input Deskripsi -->
                        <div>
                            <label for="description"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Keterangan
                                Barang/Jasa <span class="text-red-500">*</span></label>
                            <input type="text" id="description" wire:model="description"
                                class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-primary focus:border-primary sm:text-sm"
                                placeholder="Contoh: Pengisian BBM / Ganti Oli / Servis Rutin">
                            @error('description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Foto Nota -->
                        <div>
                            <label for="receipt"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Foto Bukti
                                Nota (Opsional)</label>
                            <input type="file" id="receipt" wire:model="receipt" accept="image/*"
                                class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors">
                            @error('receipt') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                            <div wire:loading wire:target="receipt" class="text-xs text-slate-500 mt-1">Mengunggah
                                file...</div>

                            @if($existing_receipt_path && !$receipt)
                                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">info</span>
                                    Sudah ada file terunggah sebelumnya.
                                </p>
                            @endif
                        </div>

                    </div>

                    <div
                        class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3 rounded-b-xl">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-indigo-600 shadow-sm transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>