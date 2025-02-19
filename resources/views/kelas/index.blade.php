<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Kelas</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <!-- Tombol Tambah Kelas -->
        <button id="tambahKelasBtn" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">
            Tambah Kelas
        </button>

        <!-- Form Tambah Kelas (Hidden Default) -->
        <div id="formKelasContainer" class="hidden">
            <form id="kelasForm">
                @csrf
                <label for="nama" class="block text-sm font-medium">Nama Kelas:</label>
                <input type="text" id="nama" name="nama" class="border p-2 w-full mb-4" required>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
                <button type="button" id="batalBtn" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
            </form>
        </div>

        <!-- Tabel Kelas -->
        <table class="w-full border text-center">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">Nama Kelas</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody id="kelasTable">
                <!-- Data akan dimuat dengan AJAX -->
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Load data kelas saat halaman dibuka
            function loadKelas() {
                $.ajax({
                    url: "{{ route('kelas.index') }}",
                    method: "GET",
                    success: function(response) {
                        let rows = "";
                        response.forEach(function(kelas) {
                            rows += `
                                <tr>
                                    <td class="border px-4 py-2">${kelas.nama}</td>
                                    <td class="border px-4 py-2">
                                        <button class="show-btn bg-green-500 text-white px-2 py-1 rounded" data-id="${kelas.id}">Lihat</button>
                                        <button class="edit-btn bg-yellow-500 text-white px-2 py-1 rounded" data-id="${kelas.id}" data-nama="${kelas.nama}">Edit</button>
                                        <button class="delete-btn bg-red-500 text-white px-2 py-1 rounded" data-id="${kelas.id}">Hapus</button>
                                    </td>
                                </tr>
                            `;
                        });
                        $("#kelasTable").html(rows);
                    }
                });
            }

            loadKelas(); // Panggil saat halaman load

            // Menampilkan form tambah kelas
            $("#tambahKelasBtn").click(function() {
                $("#formKelasContainer").removeClass("hidden");
            });

            // Menyembunyikan form saat batal
            $("#batalBtn").click(function() {
                $("#formKelasContainer").addClass("hidden");
            });

            // Submit form tambah kelas dengan AJAX
            $("#kelasForm").submit(function(e) {
                e.preventDefault();
                let namaKelas = $("#nama").val();

                $.ajax({
                    url: "{{ route('kelas.store') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        nama: namaKelas
                    },
                    success: function(response) {
                        alert(response.message);
                        loadKelas(); // Refresh data
                        $("#formKelasContainer").addClass("hidden"); // Sembunyikan form
                        $("#nama").val(""); // Reset input
                    },
                    error: function(response) {
                        alert("Terjadi kesalahan saat menambahkan kelas!");
                    }
                });
            });

            // Hapus kelas dengan AJAX
            $(document).on("click", ".delete-btn", function() {
                let id = $(this).data("id");

                if (confirm("Yakin ingin menghapus kelas ini?")) {
                    $.ajax({
                        url: "/kelas/" + id,
                        method: "DELETE",
                        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                        success: function(response) {
                            alert(response.message);
                            loadKelas(); // Refresh tabel
                        },
                        error: function() {
                            alert("Gagal menghapus kelas!");
                        }
                    });
                }
            });

            // Edit kelas dengan AJAX
            $(document).on("click", ".edit-btn", function() {
                let id = $(this).data("id");
                let nama = $(this).data("nama");
                let newNama = prompt("Ubah nama kelas:", nama);

                if (newNama && newNama !== nama) {
                    $.ajax({
                        url: "/kelas/" + id,
                        method: "PUT",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nama: newNama
                        },
                        success: function(response) {
                            alert(response.message);
                            loadKelas(); // Refresh tabel
                        },
                        error: function() {
                            alert("Gagal mengedit kelas!");
                        }
                    });
                }
            });
            // Lihat detail kelas (menggunakan alert)
            $(document).on("click", ".show-btn", function() {
                let id = $(this).data("id");

                $.ajax({
                    url: "/kelas/" + id,
                    method: "GET",
                    success: function(response) {
                        alert(`Detail Kelas\nNama Kelas: ${response.nama}`);
                    }
                });
            });
        });
    </script>
</x-app-layout>
