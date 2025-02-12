<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <h3 class="text-2xl font-semibold mb-4 text-gray-700">Data Kelas</h3>

            <!-- Container untuk data AJAX -->
            <div id="kelasContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Data akan dimasukkan lewat AJAX -->
            </div>
        </div>
    </div>

    <!-- Load jQuery untuk AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $.ajax({
                url: "{{ route('api.dashboard') }}",
                type: "GET",
                success: function(response) {
                    let kelasHtml = "";
                    
                    response.kelas.forEach(function(kelas) {
                        let guruList = kelas.gurus.map(g => `<li>${g.nama}</li>`).join("");
                        let siswaList = kelas.siswas.map(s => `<li>${s.nama}</li>`).join("");

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
                error: function() {
                    alert("Gagal mengambil data kelas!");
                }
            });
        });
    </script>

</x-app-layout>
