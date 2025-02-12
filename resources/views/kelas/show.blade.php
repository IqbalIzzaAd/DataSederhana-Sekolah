<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Detail Kelas</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-2xl font-bold" id="namaKelas">{{ $kelas->nama }}</h3>
            <p class="mt-2 text-gray-600">Jumlah Siswa: <span id="jumlahSiswa">{{ $kelas->siswas->count() }}</span></p>
            <p class="mt-2 text-gray-600">Wali Kelas: <span id="waliKelas">{{ $kelas->guru->nama ?? 'Belum ada wali kelas' }}</span></p>

            <h4 class="mt-4 text-lg font-semibold">Daftar Siswa:</h4>
            <ul id="daftarSiswa" class="mt-2">
                @foreach($kelas->siswas as $siswa)
                    <li class="text-gray-700">{{ $siswa->nama }}</li>
                @endforeach
            </ul>

            <div class="mt-4">
                <a href="{{ route('kelas.edit', $kelas->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">Edit</a>
                <a href="{{ route('kelas.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
            </div>
        </div>
    </div>

    <script>
        function loadKelas() {
            $.ajax({
                url: "{{ route('kelas.show', $kelas->id) }}",
                type: "GET",
                success: function(response) {
                    $('#namaKelas').text(response.nama);
                    $('#jumlahSiswa').text(response.siswas.length);
                    $('#waliKelas').text(response.guru ? response.guru.nama : 'Belum ada wali kelas');

                    let daftarSiswaHtml = '';
                    response.siswas.forEach(siswa => {
                        daftarSiswaHtml += `<li class="text-gray-700">${siswa.nama}</li>`;
                    });
                    $('#daftarSiswa').html(daftarSiswaHtml);
                },
                error: function(xhr) {
                    alert("Terjadi kesalahan: " + xhr.responseText);
                }
            });
        }

        $(document).ready(function() {
            loadKelas();
        });
    </script>
</x-app-layout>
