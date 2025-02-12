<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Tambah Guru</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <form id="formTambahGuru">
            @csrf
            <label class="block">Nama Guru:</label>
            <input type="text" name="nama" id="nama" class="w-full border p-2 rounded">

            <label class="block mt-4">Mengajar Kelas:</label>
            <select name="kelas_id[]" multiple id="kelas_id" class="w-full border p-2 rounded">
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                @endforeach
            </select>

            <div class="mt-4 flex space-x-2">
                <!-- Tombol Simpan -->
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>

                <!-- Tombol Kembali -->
                <a href="{{ route('guru.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
            </div>
        </form>
        <div id="successMessage" class="text-green-500 mt-2 hidden">Guru berhasil ditambahkan!</div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#formTambahGuru").submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('guru.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $("#successMessage").removeClass("hidden").text(response.message);
                        $("#nama").val("");
                        $("#kelas_id").val("");
                    },
                    error: function(xhr) {
                        alert("Gagal menambahkan guru, kelas sudah terisi guru ");
                    }
                });
            });
        });
    </script>
</x-app-layout>
