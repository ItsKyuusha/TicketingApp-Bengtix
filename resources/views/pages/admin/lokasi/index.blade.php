<x-layouts.admin title="Manajemen Lokasi">

    {{-- ALERT SUCCESS --}}
    @if (session('success'))
        <div class="toast toast-bottom toast-center z-50">
            <div class="alert alert-success">
                <span>{{ session('success') }}</span>
            </div>
        </div>

        <script>
            setTimeout(() => {
                document.querySelector('.toast')?.remove()
            }, 3000)
        </script>
    @endif

    <div class="container mx-auto p-10">
        <div class="flex">
            <h1 class="text-3xl font-semibold mb-4">Manajemen Lokasi</h1>
            <button onclick="add_modal.showModal()" class="btn btn-primary ml-auto">
                Tambah Lokasi
            </button>
        </div>

        <div class="overflow-x-auto rounded-box bg-white p-5 shadow-xs">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lokasi</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lokasis as $index => $lokasi)
                        <tr>
                            <th>{{ $index + 1 }}</th>
                            <td>{{ $lokasi->nama_lokasi }}</td>
                            <td>{{ $lokasi->created_at->format('d M Y') }}</td>
                            <td>
                                <button
                                    class="btn btn-sm btn-primary mr-2"
                                    onclick="openEditModal(this)"
                                    data-id="{{ $lokasi->id }}"
                                    data-nama="{{ $lokasi->nama_lokasi }}">
                                    Edit
                                </button>

                                <button
                                    class="btn btn-sm bg-red-500 text-white"
                                    onclick="openDeleteModal(this)"
                                    data-id="{{ $lokasi->id }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                Tidak ada data lokasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= ADD MODAL ================= --}}
    <dialog id="add_modal" class="modal">
        <form method="POST" action="{{ route('admin.lokasi.store') }}" class="modal-box">
            @csrf

            <h3 class="text-lg font-bold mb-4">Tambah Lokasi</h3>

            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Nama Lokasi</span>
                </label>
                <input
                    type="text"
                    name="nama_lokasi"
                    class="input input-bordered w-full"
                    placeholder="Contoh: Stadion Utama"
                    required>
            </div>

            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <button class="btn" type="reset" onclick="add_modal.close()">Batal</button>
            </div>
        </form>
    </dialog>

    {{-- ================= EDIT MODAL ================= --}}
    <dialog id="edit_modal" class="modal">
        <form method="POST" class="modal-box">
            @csrf
            @method('PUT')

            <h3 class="text-lg font-bold mb-4">Edit Lokasi</h3>

            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Nama Lokasi</span>
                </label>
                <input
                    type="text"
                    name="nama_lokasi"
                    id="edit_nama_lokasi"
                    class="input input-bordered w-full"
                    required>
            </div>

            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <button class="btn" type="reset" onclick="edit_modal.close()">Batal</button>
            </div>
        </form>
    </dialog>

    {{-- ================= DELETE MODAL ================= --}}
    <dialog id="delete_modal" class="modal">
        <form method="POST" class="modal-box">
            @csrf
            @method('DELETE')

            <h3 class="text-lg font-bold mb-4">Hapus Lokasi</h3>
            <p>Apakah Anda yakin ingin menghapus lokasi ini?</p>

            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Hapus</button>
                <button class="btn" type="reset" onclick="delete_modal.close()">Batal</button>
            </div>
        </form>
    </dialog>

    {{-- ================= SCRIPT ================= --}}
    <script>
        function openEditModal(button) {
            const id = button.dataset.id;
            const nama = button.dataset.nama;

            document.getElementById('edit_nama_lokasi').value = nama;

            const form = document.querySelector('#edit_modal form');
            form.action = `/admin/lokasi/${id}`;

            edit_modal.showModal();
        }

        function openDeleteModal(button) {
            const id = button.dataset.id;

            const form = document.querySelector('#delete_modal form');
            form.action = `/admin/lokasi/${id}`;

            delete_modal.showModal();
        }
    </script>

</x-layouts.admin>
