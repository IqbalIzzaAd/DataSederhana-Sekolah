<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Kelas</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <button id="tambah-kelas" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Kelas</button>
        <table class="w-full mt-4 border text-center">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">Nama Kelas</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody id="kelas-table"></tbody>
        </table>
    </div>

    <!-- Modal Form -->
    <div id="modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 id="modal-title" class="text-xl font-semibold mb-4"></h3>
            <form id="kelas-form">
                @csrf
                <input type="hidden" id="kelas-id">
                <label>Nama Kelas</label>
                <input type="text" id="kelas-nama" class="w-full px-4 py-2 border rounded-lg mb-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
                <button type="button" id="modal-close" class="bg-gray-400 text-white px-4 py-2 rounded ml-2">Batal</button>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            loadKelas();

            function loadKelas() {
                $.get("{{ route('kelas.index') }}", function (data) {
                    let rows = "";
                    data.forEach(function (kelas) {
                        rows += `
                            <tr>
                                <td class="border px-4 py-2">${kelas.nama}</td>
                                <td class="border px-4 py-2">
                                    <button class="edit-kelas bg-yellow-500 text-white px-2 py-1 rounded" data-id="${kelas.id}" data-nama="${kelas.nama}">Edit</button>
                                    <button class="hapus-kelas bg-red-500 text-white px-2 py-1 rounded" data-id="${kelas.id}">Hapus</button>
                                </td>
                            </tr>
                        `;
                    });
                    $("#kelas-table").html(rows);
                });
            }

            $("#tambah-kelas").click(function () {
                $("#modal-title").text("Tambah Kelas");
                $("#kelas-id").val("");
                $("#kelas-nama").val("");
                $("#modal").removeClass("hidden");
            });

            $(document).on("click", ".edit-kelas", function () {
                $("#modal-title").text("Edit Kelas");
                $("#kelas-id").val($(this).data("id"));
                $("#kelas-nama").val($(this).data("nama"));
                $("#modal").removeClass("hidden");
            });

            $("#kelas-form").submit(function (e) {
                e.preventDefault();
                let id = $("#kelas-id").val();
                let url = id ? "/kelas/" + id : "{{ route('kelas.store') }}";
                let method = id ? "PATCH" : "POST";

                $.ajax({
                    url: url,
                    type: method,
                    data: {
                        _token: "{{ csrf_token() }}",
                        nama: $("#kelas-nama").val(),
                    },
                    success: function (response) {
                        alert(response.message);
                        $("#modal").addClass("hidden");
                        loadKelas();
                    },
                    error: function (response) {
                        alert("Gagal menyimpan data.");
                    }
                });
            });

            $(document).on("click", ".hapus-kelas", function () {
                let id = $(this).data("id");
                if (confirm("Apakah Anda yakin ingin menghapus kelas ini?")) {
                    $.ajax({
                        url: "/kelas/" + id,
                        type: "DELETE",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function (response) {
                            alert(response.message);
                            loadKelas();
                        },
                        error: function (response) {
                            alert("Gagal menghapus kelas.");
                        }
                    });
                }
            });

            $("#modal-close").click(function () {
                $("#modal").addClass("hidden");
            });
        });
    </script>
</x-app-layout>
