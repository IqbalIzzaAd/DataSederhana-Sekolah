<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Tambah Kelas</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <form id="formTambahKelas">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Nama Kelas</label>
                <input type="text" name="nama" id="nama" class="w-full px-4 py-2 border rounded-lg">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
        </form>
        <p id="pesanSukses" class="text-green-500 mt-2 hidden">Kelas berhasil ditambahkan!</p>
    </div>
    <script>
        $(document).ready(function() {
            $('#formTambahKelas').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('kelas.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#pesanSukses').removeClass('hidden');
                        $('#nama').val('');
                    },
                    error: function(xhr) {
                        alert("Terjadi kesalahan: " + xhr.responseText);
                    }
                });
            });
        });
    </script>
</x-app-layout>
