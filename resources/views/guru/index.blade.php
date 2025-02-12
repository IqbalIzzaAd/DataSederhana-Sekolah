<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Guru</h2>
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

            <a href="{{ route('guru.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Guru</a>
        </div>

        <table class="w-full mt-4 border text-center">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">Nama Guru</th>
                    <th class="border px-4 py-2">Mengajar Kelas</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody id="guruList">
                @foreach($gurus as $guru)
                    <tr id="guruRow{{ $guru->id }}">
                        <td class="border px-4 py-2">{{ $guru->nama }}</td>
                        <td class="border px-4 py-2">
                            @foreach($guru->kelas as $kelas)
                                {{ $kelas->nama }}@if(!$loop->last), @endif
                            @endforeach
                        </td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('guru.show', $guru->id) }}" class="bg-green-500 text-white px-2 py-1 rounded">Detail</a>
                            <a href="{{ route('guru.edit', $guru->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                            <button class="delete-btn bg-red-500 text-white px-2 py-1 rounded" data-id="{{ $guru->id }}">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            // Hapus Guru dengan Konfirmasi
            $(document).on("click", ".delete-btn", function() {
                let guruId = $(this).data("id");
                let row = $("#guruRow" + guruId);

                if (confirm("Apakah Anda yakin ingin menghapus guru ini?")) {
                    $.ajax({
                        url: "/guru/" + guruId,
                        method: "DELETE",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function(response) {
                            alert(response.message);
                            row.fadeOut(); // Hilangkan baris tabel setelah berhasil dihapus
                        },
                        error: function(xhr) {
                            alert("Error: " + xhr.responseJSON.error);
                        }
                    });
                }
            });

            // Filter Kelas Otomatis
            $("#filterKelas").change(function() {
                $("#filterKelasForm").submit();
            });
        });
    </script>
</x-app-layout>
