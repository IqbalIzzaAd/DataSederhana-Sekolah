<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <h3 class="text-2xl font-semibold mb-4 text-gray-700">Data Kelas</h3>

            <!-- Loading Indicator -->
            <div id="loading" class="text-center text-gray-500">Memuat data...</div>

            <!-- Container untuk data AJAX -->
            <div id="kelasContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
        </div>
    </div>

    <!-- Load jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $.ajax({
                url: "{{ route('api.dashboard') }}",
                type: "GET",
                beforeSend: function() {
                    $("#loading").show(); // Tampilkan loading sebelum data muncul
                },
                success: function(response) {
                    $("#loading").hide(); // Sembunyikan loading setelah data muncul
                    let kelasHtml = "";

                    response.kelas.forEach(function(kelas) {
                        let guruList = kelas.gurus.length > 0 ? 
                            kelas.gurus.map(g => `<li>${g.nama}</li>`).join("") : "<li>Belum ada guru</li>";
                        let siswaList = kelas.siswas.length > 0 ? 
                            kelas.siswas.map(s => `<li>${s.nama}</li>`).join("") : "<li>Belum ada siswa</li>";

                        kelasHtml += `
                            <div class="bg-white shadow-lg rounded-lg p-6 border border-gray-200 hover:shadow-xl transition-shadow">
                                <h4 class="text-xl font-bold text-indigo-600 flex items-center">
                                    <i class="bi bi-book mr-2"></i> ${kelas.nama}
                                </h4>
                                <p class="mt-3 font-semibold text-gray-700 flex items-center">
                                    <i class="bi bi-person-badge mr-2"></i> Guru Pengajar:
                                </p>
                                <ul class="list-disc pl-5 text-gray-600">${guruList}</ul>
                                <p class="mt-3 font-semibold text-gray-700 flex items-center">
                                    <i class="bi bi-people mr-2"></i> Daftar Siswa:
                                </p>
                                <ul class="list-disc pl-5 text-gray-600">${siswaList}</ul>
                            </div>
                        `;
                    });

                    $("#kelasContainer").html(kelasHtml);
                },
                error: function(xhr) {
                    $("#loading").html("<p class='text-red-500'>Gagal mengambil data kelas!</p>");
                    console.log(xhr.responseJSON);
                }
            });
        });
    </script>
</x-app-layout>
