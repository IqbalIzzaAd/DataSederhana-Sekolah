<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Guru</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <form id="formEditGuru">
            @csrf @method('PUT')
            <label class="block">Nama Guru:</label>
            <input type="text" name="nama" id="nama" value="{{ $guru->nama }}" class="w-full border p-2 rounded">

            <label class="block mt-4">Mengajar Kelas:</label>
            <select name="kelas_id[]" multiple id="kelas_id" class="w-full border p-2 rounded">
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}" @if($guru->kelas->contains($k->id)) selected @endif>{{ $k->nama }}</option>
                @endforeach
            </select>

            <div class="mt-4 flex space-x-2">
                <!-- Tombol Simpan -->
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>

                <!-- Tombol Kembali -->
                <a href="{{ route('guru.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
            </div>
        </form>
        <div id="updateMessage" class="text-green-500 mt-2 hidden">Guru berhasil diperbarui!</div>
    </div>

    <script>
        $(document).ready(function() {
            $("#formEditGuru").submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('guru.update', $guru->id) }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $("#updateMessage").removeClass("hidden").text(response.message);
                    },
                    error: function(xhr) {
                        alert("Gagal menambahkan guru, kelas sudah terisi guru");
                    }
                });
            });
        });
    </script>
</x-app-layout>
