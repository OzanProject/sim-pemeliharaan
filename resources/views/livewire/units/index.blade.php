<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\Unit;
use Illuminate\Validation\Rule;

new #[Layout('backend.layouts.app')] class extends Component {
    public $units;

    // Form Properties
    public $unit_id;
    public $name;
    public $plate_number;
    public $type;
    public $status = 'active';

    // State 
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadUnits();
    }

    public function loadUnits()
    {
        $this->units = Unit::orderBy('name', 'asc')->get();
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
        $this->unit_id = null;
        $this->name = '';
        $this->plate_number = '';
        $this->type = '';
        $this->status = 'active';
        $this->isEditMode = false;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'plate_number' => 'nullable|string|max:50',
            'type' => 'nullable|string|max:100',
            'status' => ['required', Rule::in(['active', 'inactive', 'maintenance'])],
        ];

        $this->validate($rules);

        Unit::updateOrCreate(
            ['id' => $this->unit_id],
            [
                'name' => $this->name,
                'plate_number' => $this->plate_number,
                'type' => $this->type,
                'status' => $this->status,
            ]
        );

        $this->dispatch('swal', title: $this->isEditMode ? 'Data Unit Kendaraan berhasil diperbarui.' : 'Data Unit Kendaraan berhasil ditambahkan.', icon: 'success');

        $this->closeModal();
        $this->loadUnits();
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        $this->unit_id = $unit->id;
        $this->name = $unit->name;
        $this->plate_number = $unit->plate_number;
        $this->type = $unit->type;
        $this->status = $unit->status;

        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        Unit::findOrFail($id)->delete();
        $this->dispatch('swal', title: 'Data Unit Kendaraan berhasil dihapus.', icon: 'success');
        $this->loadUnits();
    }
}; ?>

<div class="space-y-6">
    <!-- Header Page & Add Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Master Data Kendaraan</h2>
            <p class="text-slate-500 mt-1 text-sm">Kelola daftar kendaraan dinas dan status operasionalnya.</p>
        </div>
        <button wire:click="openModal"
            class="flex items-center gap-2 px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-indigo-600 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Tambah Kendaraan
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
                            No</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400">
                            Unit Bagian</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400">
                            Plat Nomor</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400">
                            Tipe Kendaraan</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400">
                            Status</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400 text-right">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($units as $index => $unit)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-300">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-slate-900 dark:text-slate-100">{{ $unit->name }}
                            </td>
                            <td class="px-6 py-4 text-sm font-mono text-slate-600 dark:text-slate-300">
                                {{ $unit->plate_number ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                {{ $unit->type ?? 'Tidak Ditentukan' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($unit->status === 'active')
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                                        Aktif
                                    </span>
                                @elseif($unit->status === 'inactive')
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 dark:bg-slate-500/10 dark:text-slate-400">
                                        Nonaktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-500">
                                        Perawatan
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="edit({{ $unit->id }})"
                                        class="p-2 text-slate-400 hover:text-primary hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-lg transition-colors"
                                        title="Edit Data">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <button wire:click="delete({{ $unit->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus data kendaraan ini? Semua anggaran dan transaksi yang terhubung juga akan ikut terhapus!"
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
                                    class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 mb-2">directions_car</span>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada data
                                    kendaraan
                                    dinas yang ditambahkan.</p>
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
                        {{ $isEditMode ? 'Edit Data Kendaraan' : 'Tambah Kendaraan Baru' }}
                    </h3>
                    <button wire:click="closeModal"
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="p-6 space-y-4">

                        <!-- Input Nama Unit -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama
                                Bagian/Unit <span class="text-red-500">*</span></label>
                            <input type="text" id="name" wire:model="name"
                                class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-primary focus:border-primary sm:text-sm"
                                placeholder="Contoh: Bagian Umum / Sekretariat">
                            @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Input Plat Nomor -->
                            <div>
                                <label for="plate_number"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Plat
                                    Nomor</label>
                                <input type="text" id="plate_number" wire:model="plate_number"
                                    class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-primary focus:border-primary sm:text-sm uppercase"
                                    placeholder="B 1234 CD">
                                @error('plate_number') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Input Tipe -->
                            <div>
                                <label for="type"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tipe
                                    Kendaraan</label>
                                <input type="text" id="type" wire:model="type"
                                    class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-primary focus:border-primary sm:text-sm"
                                    placeholder="Contoh: MPV / Minibus">
                                @error('type') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Pilihan Status -->
                        <div>
                            <label for="status"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status
                                Operasional
                                <span class="text-red-500">*</span></label>
                            <select id="status" wire:model="status"
                                class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-primary focus:border-primary sm:text-sm">
                                <option value="active">🟢 Aktif Beroperasi</option>
                                <option value="inactive">⚪ Sedang Nonaktif</option>
                                <option value="maintenance">🟠 Masuk Bengkel (Perawatan)</option>
                            </select>
                            @error('status') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
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