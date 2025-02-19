<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Guru</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <div class="flex flex-wrap justify-between items-center mb-4 gap-3">
        <button id="tambahGuruBtn" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">Tambah Guru</button>
        <div class="relative">
            <input type="text" id="search" class="border border-gray-300 rounded px-3 py-1 pl-8" placeholder="Cari guru...">
            <i class="absolute left-2 top-1 text-gray-500 bi bi-search"></i>
        </div>
    </div>

        <table class="w-full border text-center">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">Nama</th>
                    <th class="border px-4 py-2">NIP</th>
                    <th class="border px-4 py-2">Mapel</th>
                    <th class="border px-4 py-2">Kelas</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody id="guruTable">
                <!-- Data akan di-load dengan AJAX -->
            </tbody>
        </table>
    </div>

    <!-- MODAL FORM GURU -->
    <div id="guruModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded w-1/3">
            <h2 id="modalTitle" class="text-xl font-bold mb-4">Tambah Guru</h2>
            <form id="guruForm">
                @csrf
                <input type="hidden" id="guru_id">

                <label class="block">Nama</label>
                <input type="text" id="nama" name="nama" class="border rounded w-full px-3 py-2 mt-1" required>

                <label class="block mt-2">NIP</label>
                <input type="text" id="nip" name="nip" class="border rounded w-full px-3 py-2 mt-1" required>

                <label class="block mt-2">Mata Pelajaran</label>
                <input type="text" id="mapel" name="mapel" class="border rounded w-full px-3 py-2 mt-1" required>

                <label class="block mt-2">Pilih Kelas</label>
                <select id="kelas" name="kelas[]" multiple class="border rounded w-full px-3 py-2 mt-1">
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
        let allGurus = []; // Simpan data guru untuk pencarian

        function loadGurus() {
            $.ajax({
                url: "{{ route('guru.getGurus') }}",
                method: "GET",
                success: function(response) {
                    allGurus = response; // Simpan data guru untuk filter pencarian
                    updateTable(allGurus); // Tampilkan data awal
                }
            });
        }

        function updateTable(data) {
            let rows = "";
            data.forEach(function(guru) {
                let kelasList = guru.kelas.map(k => k.nama).join(', ');

                rows += `
                    <tr>
                        <td class="border px-4 py-2">${guru.nama}</td>
                        <td class="border px-4 py-2">${guru.nip}</td>
                        <td class="border px-4 py-2">${guru.mapel}</td>
                        <td class="border px-4 py-2">${kelasList}</td>
                        <td class="border px-4 py-2">
                            <button class="bg-green-500 text-white px-2 py-1 rounded show-btn" data-id="${guru.id}">Lihat</button>
                            <button class="bg-yellow-500 text-white px-2 py-1 rounded edit-btn" data-id="${guru.id}">Edit</button>
                            <button class="bg-red-500 text-white px-2 py-1 rounded delete-btn" data-id="${guru.id}">Hapus</button>
                        </td>
                    </tr>
                `;
            });
            $("#guruTable").html(rows);
        }

        $(document).ready(function() {
            loadGurus();

            // Pencarian guru berdasarkan nama
            $("#search").on("keyup", function() {
                let keyword = $(this).val().toLowerCase();
                let filteredData = allGurus.filter(guru => guru.nama.toLowerCase().includes(keyword));
                updateTable(filteredData);
            });

            $("#tambahGuruBtn").on("click", function() {
                $("#guruForm")[0].reset();
                $("#guru_id").val("");
                $("#modalTitle").text("Tambah Guru");
                $("#guruModal").removeClass("hidden");
            });

            $("#closeModal").on("click", function() {
                $("#guruModal").addClass("hidden");
            });

            $("#guruForm").on("submit", function(e) {
                e.preventDefault();

                let id = $("#guru_id").val();
                let url = id ? "/guru/" + id : "{{ route('guru.store') }}";
                let method = id ? "PUT" : "POST";

                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        _token: "{{ csrf_token() }}",
                        nip: $("#nip").val(),
                        nama: $("#nama").val(),
                        mapel: $("#mapel").val(),
                        kelas: $("#kelas").val()
                    },
                    success: function(response) {
                        alert(response.message);
                        $("#guruModal").addClass("hidden");
                        loadGurus();
                    }
                });
            });

            $(document).on("click", ".edit-btn", function() {
                let id = $(this).data("id");

                $.ajax({
                    url: "/guru/" + id,
                    method: "GET",
                    success: function(response) {
                        $("#guru_id").val(response.id);
                        $("#nama").val(response.nama);
                        $("#nip").val(response.nip);
                        $("#mapel").val(response.mapel);
                        $("#kelas").val(response.kelas.map(k => k.id));

                        $("#modalTitle").text("Edit Guru");
                        $("#guruModal").removeClass("hidden");
                    }
                });
            });

            $(document).on("click", ".show-btn", function() {
                let id = $(this).data("id");

                $.ajax({
                    url: "/guru/" + id,
                    method: "GET",
                    success: function(response) {
                        alert(`Nama: ${response.nama}\nNIP: ${response.nip}\nMapel: ${response.mapel}\nKelas: ${response.kelas.map(k => k.nama).join(", ")}`);
                    }
                });
            });

            $(document).on("click", ".delete-btn", function() {
                let id = $(this).data("id");

                if (confirm("Yakin ingin menghapus guru ini?")) {
                    $.ajax({
                        url: "/guru/" + id,
                        method: "DELETE",
                        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                        success: function(response) {
                            alert(response.message);
                            loadGurus();
                        }
                    });
                }
            });
        });
    </script>
</x-app-layout>
