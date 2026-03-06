<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

new #[Layout('backend.layouts.app')] class extends Component {
    use WithPagination;

    public $search = '';
    public $userId, $name, $email, $password, $password_confirmation;
    public $isEditMode = false;
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $userToDelete = null;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->userId,
        ];

        if (!$this->isEditMode || !empty($this->password)) {
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        return $rules;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['userId', 'name', 'email', 'password', 'password_confirmation', 'isEditMode']);
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function editUser($id)
    {
        $this->resetValidation();
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function saveUser()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->userId], $data);

        $this->closeModal();
        $this->dispatch('swal', title: $this->isEditMode ? 'Data pengguna berhasil diperbarui!' : 'Pengguna baru berhasil ditambahkan!');
        $this->reset(['userId', 'name', 'email', 'password', 'password_confirmation', 'isEditMode']);
    }

    public function confirmDelete($id)
    {
        if ($id == 1) {
            session()->flash('error', 'Administrator utama tidak dapat dihapus!');
            return;
        }
        $this->userToDelete = $id;
        $this->isDeleteModalOpen = true;
    }

    public function deleteUser()
    {
        if ($this->userToDelete && $this->userToDelete != 1) {
            User::destroy($this->userToDelete);
            $this->dispatch('swal', title: 'Data pengguna berhasil dihapus!', icon: 'success');
        } else {
            $this->dispatch('swal', title: 'Super Admin tidak boleh dihapus!', icon: 'error');
        }
        $this->isDeleteModalOpen = false;
        $this->userToDelete = null;
    }

    public function with(): array
    {
        $users = User::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%');
        })
            ->orderBy('id', 'asc') // Memastikan User 1 selalu di atas
            ->paginate(10);

        return [
            'users' => $users,
        ];
    }
}; ?>

<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Manajemen User</h2>
            <p class="text-slate-500 mt-1 text-sm">Kelola daftar pengguna yang memiliki akses ke dalam sistem.</p>
        </div>
        <button wire:click="openModal"
            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-indigo-600 transition-colors flex items-center gap-2 shadow-sm font-medium text-sm w-full sm:w-auto justify-center">
            <span class="material-symbols-outlined text-[20px]">person_add</span>
            Tambah User
        </button>
    </div>

    <div
        class="bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <!-- Search -->
        <div
            class="p-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex items-center justify-between">
            <div class="relative w-full sm:w-72">
                <span
                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text[20px]">search</span>
                <input wire:model.live="search" type="text" placeholder="Cari nama atau email..."
                    class="w-full pl-10 pr-4 py-2 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary dark:bg-slate-800 dark:text-slate-200 transition-shadow">
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-100 dark:border-slate-800 text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        <th class="px-6 py-4 font-semibold w-16">No</th>
                        <th class="px-6 py-4 font-semibold">Nama Pengguna</th>
                        <th class="px-6 py-4 font-semibold">Email</th>
                        <th class="px-6 py-4 font-semibold">Status / Peran</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($users as $index => $user)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                                {{ $users->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-900 dark:text-slate-100">{{ $user->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-600 dark:text-slate-400">{{ $user->email }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->id == 1)
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-md bg-primary/10 text-primary text-xs font-semibold ring-1 ring-inset ring-primary/20">
                                        Super Admin
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-medium ring-1 ring-inset ring-slate-200 dark:ring-slate-700">
                                        User Biasa
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="editUser({{ $user->id }})"
                                        class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-lg transition-colors"
                                        title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </button>
                                    @if($user->id != 1)
                                        <button wire:click="confirmDelete({{ $user->id }})"
                                            class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors"
                                            title="Hapus">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    @else
                                        <button disabled
                                            class="p-2 text-slate-300 dark:text-slate-600 rounded-lg cursor-not-allowed"
                                            title="Admin Utama tidak bisa dihapus">
                                            <span class="material-symbols-outlined text-[20px]">lock</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl mb-2 opacity-50">group_off</span>
                                    <p>Tidak ada data pengguna ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Form -->
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" aria-hidden="true"
                    wire:click="closeModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div
                    class="inline-block w-full text-left align-bottom transition-all transform bg-white dark:bg-slate-900 rounded-xl sm:my-8 sm:align-middle sm:max-w-lg shadow-xl border border-slate-100 dark:border-slate-800 overflow-hidden">
                    <form wire:submit.prevent="saveUser">
                        <div
                            class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100" id="modal-title">
                                {{ $isEditMode ? 'Edit Data Pengguna' : 'Tambah Pengguna Baru' }}
                            </h3>
                            <button type="button" wire:click="closeModal"
                                class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>

                        <div class="px-6 py-4 space-y-4">
                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama
                                    Lengkap <span class="text-red-500">*</span></label>
                                <input wire:model.defer="name" type="text"
                                    class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200"
                                    required>
                                @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email <span
                                        class="text-red-500">*</span></label>
                                <input wire:model.defer="email" type="email"
                                    class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200"
                                    required>
                                @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                    Password
                                    @if(!$isEditMode) <span class="text-red-500">*</span> @else <span
                                        class="text-xs text-slate-400 font-normal">(Kosongkan jika tidak ingin
                                    diubah)</span> @endif
                                </label>
                                <input wire:model.defer="password" type="password"
                                    class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200"
                                    {{ !$isEditMode ? 'required' : '' }}>
                                @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Konfirmasi
                                    Password</label>
                                <input wire:model.defer="password_confirmation" type="password"
                                    class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200"
                                    {{ !$isEditMode ? 'required' : '' }}>
                            </div>
                        </div>

                        <div
                            class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex justify-end gap-3">
                            <button type="button" wire:click="closeModal"
                                class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-indigo-600 transition-colors flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">save</span>
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($isDeleteModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" aria-hidden="true"
                    wire:click="$set('isDeleteModalOpen', false)"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block w-full text-left align-bottom transition-all transform bg-white dark:bg-slate-900 rounded-xl sm:my-8 sm:align-middle sm:max-w-md shadow-xl border border-slate-100 dark:border-slate-800 overflow-hidden">
                    <div class="px-6 py-6 pb-4">
                        <div
                            class="flex items-center justify-center size-12 mx-auto bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full mb-4">
                            <span class="material-symbols-outlined text-2xl">warning</span>
                        </div>
                        <h3 class="text-lg font-bold text-center text-slate-900 dark:text-slate-100 mb-2">Hapus Pengguna
                        </h3>
                        <p class="text-sm text-center text-slate-500 dark:text-slate-400">
                            Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>
                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 flex justify-center gap-3">
                        <button type="button" wire:click="$set('isDeleteModalOpen', false)"
                            class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors w-full sm:w-auto">
                            Batal
                        </button>
                        <button type="button" wire:click="deleteUser"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors w-full sm:w-auto flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>