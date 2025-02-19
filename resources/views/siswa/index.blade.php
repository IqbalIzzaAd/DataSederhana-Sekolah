<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Siswa</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <div class="flex flex-wrap justify-between items-center mb-4 gap-3">
        <button id="tambahSiswaBtn" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">Tambah Siswa</button>

        <div class="relative">
            <input type="text" id="search" class="border border-gray-300 rounded px-3 py-1 pl-8" placeholder="Cari siswa...">
            <i class="absolute left-2 top-1 text-gray-500 bi bi-search"></i>
        </div>
    </div>

        <table class="w-full border text-center">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">NIS</th>
                    <th class="border px-4 py-2">Nama</th>
                    <th class="border px-4 py-2">Kelas</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody id="siswaTable">
                <!-- Data akan di-load dengan AJAX -->
            </tbody>
        </table>
    </div>

    <!-- MODAL FORM SISWA -->
    <div id="siswaModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded w-1/3">
            <h2 id="modalTitle" class="text-xl font-bold mb-4">Tambah Siswa</h2>
            <form id="siswaForm">
                @csrf
                <input type="hidden" id="siswa_id">

                <label class="block">NIS</label>
                <input type="text" id="nis" name="nis" class="border rounded w-full px-3 py-2 mt-1" required>

                <label class="block mt-2">Nama</label>
                <input type="text" id="nama" name="nama" class="border rounded w-full px-3 py-2 mt-1" required>

                <label class="block mt-2">Pilih Kelas</label>
                <select id="kelas_id" name="kelas_id" class="border rounded w-full px-3 py-2 mt-1">
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>

                <div class="mt-4 flex justify-end">
                    <button type="button" id="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Batal</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let allData = []; // Menyimpan semua data siswa untuk pencarian

        $(document).ready(function() {
            loadSiswa();

            function loadSiswa() {
                $.ajax({
                    url: "{{ route('siswa.index') }}",
                    method: "GET",
                    success: function(response) {
                        allData = response; // Simpan data siswa
                        updateTable(allData); // Tampilkan data awal
                    }
                });
            }

            function updateTable(data) {
                let rows = "";
                data.forEach(function(siswa) {
                    let kelas = siswa.kelas ? siswa.kelas.nama : "Tidak ada";
                    rows += `
                        <tr>
                            <td class="border px-4 py-2">${siswa.nis}</td>
                            <td class="border px-4 py-2">${siswa.nama}</td>
                            <td class="border px-4 py-2">${kelas}</td>
                            <td class="border px-4 py-2">
                                <button class="bg-green-500 text-white px-2 py-1 rounded show-btn" data-id="${siswa.id}">Lihat</button>
                                <button class="bg-yellow-500 text-white px-2 py-1 rounded edit-btn" data-id="${siswa.id}">Edit</button>
                                <button class="bg-red-500 text-white px-2 py-1 rounded delete-btn" data-id="${siswa.id}">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
                $("#siswaTable").html(rows);
            }

            // Pencarian siswa berdasarkan nama
            $("#search").on("keyup", function() {
                let keyword = $(this).val().toLowerCase();
                let filteredData = allData.filter(siswa => siswa.nama.toLowerCase().includes(keyword));
                updateTable(filteredData);
            });

            // Buka modal tambah siswa
            $("#tambahSiswaBtn").on("click", function() {
                $("#siswaForm")[0].reset();
                $("#siswa_id").val("");
                $("#modalTitle").text("Tambah Siswa");
                $("#siswaModal").removeClass("hidden");
            });

            // Tutup modal
            $("#closeModal").on("click", function() {
                $("#siswaModal").addClass("hidden");
            });

            // Tambah/Edit siswa
            $("#siswaForm").on("submit", function(e) {
                e.preventDefault();

                let id = $("#siswa_id").val();
                let url = id ? "/siswa/" + id : "{{ route('siswa.store') }}";
                let method = id ? "PUT" : "POST";

                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        _token: "{{ csrf_token() }}",
                        nis: $("#nis").val(),
                        nama: $("#nama").val(),
                        kelas_id: $("#kelas_id").val()
                    },
                    success: function(response) {
                        alert(response.message);
                        $("#siswaModal").addClass("hidden");
                        loadSiswa();
                    }
                });
            });

            // Edit siswa
            $(document).on("click", ".edit-btn", function() {
                let id = $(this).data("id");

                $.ajax({
                    url: "/siswa/" + id,
                    method: "GET",
                    success: function(response) {
                        $("#siswa_id").val(response.id);
                        $("#nis").val(response.nis);
                        $("#nama").val(response.nama);
                        $("#kelas_id").val(response.kelas_id);

                        $("#modalTitle").text("Edit Siswa");
                        $("#siswaModal").removeClass("hidden");
                    }
                });
            });

            // Lihat siswa
            $(document).on("click", ".show-btn", function() {
                let id = $(this).data("id");

                $.ajax({
                    url: "/siswa/" + id,
                    method: "GET",
                    success: function(response) {
                        alert(`NIS: ${response.nis}\nNama: ${response.nama}\nKelas: ${response.kelas.nama}`);
                    }
                });
            });

            // Hapus siswa
            $(document).on("click", ".delete-btn", function() {
                let id = $(this).data("id");

                if (confirm("Yakin ingin menghapus siswa ini?")) {
                    $.ajax({
                        url: "/siswa/" + id,
                        method: "DELETE",
                        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                        success: function(response) {
                            alert(response.message);
                            loadSiswa();
                        }
                    });
                }
            });

        });
    </script>
</x-app-layout>
