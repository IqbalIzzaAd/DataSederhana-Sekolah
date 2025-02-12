<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Kelas</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <form id="formEditKelas">
            @csrf @method('PATCH')
            <div class="mb-4">
                <label class="block text-gray-700">Nama Kelas</label>
                <input type="text" name="nama" id="nama" class="w-full px-4 py-2 border rounded-lg" value="{{ $kelas->nama }}">
            </div>
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Update</button>
        </form>
        <p id="pesanUpdate" class="text-green-500 mt-2 hidden">Kelas berhasil diperbarui!</p>
    </div>

    <script>
        $(document).ready(function() {
            $('#formEditKelas').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('kelas.update', $kelas->id) }}",
                    type: "PATCH",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#pesanUpdate').removeClass('hidden');
                    },
                    error: function(xhr) {
                        alert("Terjadi kesalahan: " + xhr.responseText);
                    }
                });
            });
        });
    </script>
</x-app-layout>
