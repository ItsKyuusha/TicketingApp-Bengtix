<x-layouts.admin title="Manajemen Tipe Pembayaran">
    <div class="container mx-auto p-10">

        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="toast toast-bottom toast-center z-50">
                <div class="alert alert-success">
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            <script>
                setTimeout(() => document.querySelector('.toast')?.remove(), 3000);
            </script>
        @endif

        <div class="flex mb-6">
            <h1 class="text-3xl font-semibold">Manajemen Tipe Pembayaran</h1>
            <button onclick="add_modal.showModal()" class="btn btn-primary ml-auto">
                Tambah Tipe Pembayaran
            </button>
        </div>

        <div class="overflow-x-auto rounded-box bg-white p-5 shadow-xs">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Tipe Pembayaran</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($types as $index => $type)
                        <tr>
                            <th>{{ $index + 1 }}</th>
                            <td>{{ $type->nama }}</td>
                            <td>{{ $type->created_at->format('d M Y') }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary"
                                    onclick="openEditModal(this)"
                                    data-id="{{ $type->id }}"
                                    data-nama="{{ $type->nama }}">
                                    Edit
                                </button>

                                <button class="btn btn-sm bg-red-500 text-white"
                                    onclick="openDeleteModal(this)"
                                    data-id="{{ $type->id }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                Belum ada tipe pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ADD MODAL --}}
    <dialog id="add_modal" class="modal">
        <form method="POST" action="{{ route('admin.payment-types.store') }}" class="modal-box">
            @csrf
            <h3 class="text-lg font-bold mb-4">Tambah Tipe Pembayaran</h3>

            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Nama Tipe Pembayaran</span>
                </label>
                <input type="text" name="nama"
                    class="input input-bordered w-full"
                    placeholder="Contoh: Transfer Bank / E-Wallet / Cash"
                    required>
            </div>

            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <button class="btn" type="reset" onclick="add_modal.close()">Batal</button>
            </div>
        </form>
    </dialog>

    {{-- EDIT MODAL --}}
    <dialog id="edit_modal" class="modal">
        <form method="POST" class="modal-box">
            @csrf
            @method('PUT')

            <h3 class="text-lg font-bold mb-4">Edit Tipe Pembayaran</h3>

            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Nama Tipe Pembayaran</span>
                </label>
                <input type="text" name="nama" id="edit_nama"
                    class="input input-bordered w-full"
                    required>
            </div>

            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <button class="btn" type="reset" onclick="edit_modal.close()">Batal</button>
            </div>
        </form>
    </dialog>

    {{-- DELETE MODAL --}}
    <dialog id="delete_modal" class="modal">
        <form method="POST" class="modal-box">
            @csrf
            @method('DELETE')

            <h3 class="text-lg font-bold mb-4">Hapus Tipe Pembayaran</h3>
            <p>Apakah Anda yakin ingin menghapus tipe pembayaran ini?</p>

            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Hapus</button>
                <button class="btn" type="reset" onclick="delete_modal.close()">Batal</button>
            </div>
        </form>
    </dialog>

    <script>
        function openEditModal(button) {
            const id = button.dataset.id;
            const nama = button.dataset.nama;

            document.getElementById('edit_nama').value = nama;
            document.querySelector('#edit_modal form').action = `/admin/payment-types/${id}`;

            edit_modal.showModal();
        }

        function openDeleteModal(button) {
            const id = button.dataset.id;
            document.querySelector('#delete_modal form').action = `/admin/payment-types/${id}`;
            delete_modal.showModal();
        }
    </script>
</x-layouts.admin>
