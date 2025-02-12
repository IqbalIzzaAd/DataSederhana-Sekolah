<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Siswa</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <div class="flex justify-between items-center">
            <!-- Dropdown Filter Kelas -->
            <form id="filterKelasForm">
                <select name="kelas_id" id="filterKelas" class="border px-4 py-2 rounded">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('siswa.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Siswa</a>
        </div>

        <table class="w-full mt-4 border text-center">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">Nama Siswa</th>
                    <th class="border px-4 py-2">Kelas</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody id="siswaList">
                @foreach($siswas as $siswa)
                    <tr id="siswaRow{{ $siswa->id }}">
                        <td class="border px-4 py-2">{{ $siswa->nama }}</td>
                        <td class="border px-4 py-2">{{ $siswa->kelas->nama }}</td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('siswa.show', $siswa->id) }}" class="bg-green-500 text-white px-2 py-1 rounded">Detail</a>
                            <a href="{{ route('siswa.edit', $siswa->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                            <button class="delete-btn bg-red-500 text-white px-2 py-1 rounded" data-id="{{ $siswa->id }}">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        $(document).ready(function() {
            // Filter kelas otomatis dengan AJAX
            $("#filterKelas").change(function() {
                let kelasId = $(this).val();
                $.ajax({
                    url: "{{ route('siswa.index') }}",
                    method: "GET",
                    data: { kelas_id: kelasId },
                    success: function(response) {
                        let rows = "";
                        response.siswas.forEach(siswa => {
                            rows += `
                                <tr id="siswaRow${siswa.id}">
                                    <td class="border px-4 py-2">${siswa.nama}</td>
                                    <td class="border px-4 py-2">${siswa.kelas.nama}</td>
                                    <td class="border px-4 py-2">
                                        <a href="/siswa/${siswa.id}" class="bg-green-500 text-white px-2 py-1 rounded">Detail</a>
                                        <a href="/siswa/${siswa.id}/edit" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                                        <button class="delete-btn bg-red-500 text-white px-2 py-1 rounded" data-id="${siswa.id}">Hapus</button>
                                    </td>
                                </tr>`;
                        });
                        $("#siswaList").html(rows);
                    }
                });
            });

            // Hapus siswa dengan AJAX
            $(document).on("click", ".delete-btn", function() {
                let siswaId = $(this).data("id");
                let row = $("#siswaRow" + siswaId);

                if (confirm("Apakah Anda yakin ingin menghapus siswa ini?")) {
                    $.ajax({
                        url: "/siswa/" + siswaId,
                        method: "DELETE",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function(response) {
                            alert(response.message);
                            row.fadeOut();
                        },
                        error: function(xhr) {
                            alert("Gagal menghapus siswa!");
                        }
                    });
                }
            });
        });
    </script>
</x-app-layout>
