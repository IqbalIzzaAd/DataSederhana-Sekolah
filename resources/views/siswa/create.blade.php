<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Tambah Siswa</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <form id="formTambahSiswa">
            @csrf
            <label class="block">Nama Siswa:</label>
            <input type="text" name="nama" id="nama" class="w-full border p-2 rounded">

            <label class="block mt-4">Pilih Kelas:</label>
            <select name="kelas_id" id="kelas_id" class="w-full border p-2 rounded">
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Simpan</button>
            <a href="{{ route('siswa.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mt-4">Kembali</a>
        </form>
        
        <div id="successMessage" class="text-green-500 mt-2 hidden">Siswa berhasil ditambahkan!</div>
        <div id="errorMessage" class="text-red-500 mt-2 hidden"></div>
    </div>

    <script>
        $(document).ready(function() {
            $("#formTambahSiswa").submit(function(e) {
                e.preventDefault(); // Mencegah reload halaman

                $.ajax({
                    url: "{{ route('siswa.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $("#successMessage").removeClass("hidden").text(response.message);
                        $("#errorMessage").addClass("hidden");
                        $("#nama").val("");
                        $("#kelas_id").val("");
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = "Terjadi kesalahan!";
                        if (errors) {
                            errorMessage = Object.values(errors).map(error => error[0]).join("<br>");
                        }
                        $("#errorMessage").removeClass("hidden").html(errorMessage);
                    }
                });
            });
        });
    </script>
</x-app-layout>
