<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-3">Dashboard</h2>
    </x-slot>

    <div class="py-5 max-w-7xl mx-auto">
        <!-- Navigation Tabs -->
        <div class="mb-4 border-b">
            <nav class="flex">
                <button class="tab-button px-4 py-2 text-gray-700 border border-gray-300 rounded-t-lg bg-gray-100 active" data-type="siswa">Data Siswa</button>
                <button class="tab-button px-4 py-2 text-gray-700 border border-gray-300 rounded-t-lg" data-type="guru">Data Guru</button>
                <button class="tab-button px-4 py-2 text-gray-700 border border-gray-300 rounded-t-lg" data-type="siswa-guru">Data Siswa dan Guru</button>
            </nav>
        </div>

        <!-- Show Entries & Search -->
        <div class="flex flex-wrap justify-between items-center mb-4 gap-3">
            <div class="flex items-center">
                <label for="entries" class="mr-2 text-gray-700">Show</label>
                <select id="entries" class="border border-gray-300 rounded pl-3 px-7 py-1">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="5">5</option>
                    
                </select>
                <span class="ml-2 text-gray-700">entries</span>
            </div>

            <div class="relative">
                <input type="text" id="search" class="border border-gray-300 rounded px-3 py-1 pl-8" placeholder="Cari kelas...">
                <i class="absolute left-2 top-1 text-gray-500 bi bi-search"></i>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="loading" class="text-center text-gray-500">Memuat data...</div>

        <!-- Container untuk data AJAX -->
        <div id="kelasContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-4">
            <div id="showingInfo" class="text-gray-700"></div>
            <div>
                <button id="prevPage" class="px-3 py-1 border rounded bg-gray-300 text-gray-700">Previous</button>
                <span id="currentPage" class="px-3 text-gray-700">1</span>
                <button id="nextPage" class="px-3 py-1 border rounded bg-gray-300 text-gray-700">Next</button>
            </div>
        </div>
    </div>

    <!-- Load jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            let currentPage = 1;
            let entriesPerPage = 1;
            let totalEntries = 0;
            let allData = [];
            let filteredData = [];
            let currentTab = "siswa"; // Default tab

            function fetchData() {
                $.ajax({
                    url: "{{ route('api.dashboard') }}",
                    type: "GET",
                    beforeSend: function() {
                        $("#loading").show();
                        $("#kelasContainer").html("");
                    },
                    success: function(response) {
                        $("#loading").hide();
                        allData = response.kelas;
                        filteredData = allData; // Inisialisasi data filter
                        totalEntries = filteredData.length;
                        updateDisplay();
                    },
                    error: function(xhr) {
                        $("#loading").html("<p class='text-red-500'>Gagal mengambil data kelas!</p>");
                        console.log(xhr.responseJSON);
                    }
                });
            }

            function updateDisplay() {
                let start = (currentPage - 1) * entriesPerPage;
                let end = start + entriesPerPage;
                let dataToShow = filteredData.slice(start, end);

                let kelasHtml = "";
                dataToShow.forEach(function(kelas) {
                    let guruList = kelas.gurus.length > 0 ? 
                        kelas.gurus.map(g => `<li>${g.nama} (NIP: ${g.nip})</li>`).join("") : "<li>Belum ada guru</li>";
                    let siswaList = kelas.siswas.length > 0 ? 
                        kelas.siswas.map(s => `<li>${s.nama} (NIS: ${s.nis})</li>`).join("") : "<li>Belum ada siswa</li>";

                    if (currentTab === "siswa") {
                        kelasHtml += `
                            <div class="bg-white shadow-lg rounded-lg p-6 border border-gray-200 hover:shadow-xl transition-shadow">
                                <h4 class="text-xl font-bold text-indigo-600">${kelas.nama}</h4>
                                <p class="mt-3 font-semibold text-gray-700">Daftar Siswa:</p>
                                <ul class="list-disc pl-5 text-gray-600">${siswaList}</ul>
                            </div>
                        `;
                    } else if (currentTab === "guru") {
                        kelasHtml += `
                            <div class="bg-white shadow-lg rounded-lg p-6 border border-gray-200 hover:shadow-xl transition-shadow">
                                <h4 class="text-xl font-bold text-indigo-600">${kelas.nama}</h4>
                                <p class="mt-3 font-semibold text-gray-700">Guru Pengajar:</p>
                                <ul class="list-disc pl-5 text-gray-600">${guruList}</ul>
                            </div>
                        `;
                    } else {
                        kelasHtml += `
                            <div class="bg-white shadow-lg rounded-lg p-6 border border-gray-200 hover:shadow-xl transition-shadow">
                                <h4 class="text-xl font-bold text-indigo-600">${kelas.nama}</h4>
                                <p class="mt-3 font-semibold text-gray-700">Guru Pengajar:</p>
                                <ul class="list-disc pl-5 text-gray-600">${guruList}</ul>
                                <p class="mt-3 font-semibold text-gray-700">Daftar Siswa:</p>
                                <ul class="list-disc pl-5 text-gray-600">${siswaList}</ul>
                            </div>
                        `;
                    }
                });

                $("#kelasContainer").html(kelasHtml);
                updatePaginationInfo();
            }

            function updatePaginationInfo() {
                let start = (currentPage - 1) * entriesPerPage + 1;
                let end = Math.min(start + entriesPerPage - 1, totalEntries);
                $("#showingInfo").text(`Showing ${start} to ${end} of ${totalEntries} entries`);

                $("#prevPage").prop("disabled", currentPage === 1);
                $("#nextPage").prop("disabled", end >= totalEntries);

                $("#currentPage").text(currentPage);
            }

            $("#search").on("keyup", function() {
                let keyword = $(this).val().toLowerCase();
                filteredData = allData.filter(kelas => kelas.nama.toLowerCase().includes(keyword));
                totalEntries = filteredData.length;
                currentPage = 1;
                updateDisplay();
            });

            $(".tab-button").click(function() {
                $(".tab-button").removeClass("bg-gray-100 active");
                $(this).addClass("bg-gray-100 active");
                currentTab = $(this).data("type");
                updateDisplay();
            });

            $("#entries").change(function() {
                entriesPerPage = parseInt($(this).val());
                currentPage = 1;
                updateDisplay();
            });

            $("#prevPage").click(() => { if (currentPage > 1) { currentPage--; updateDisplay(); } });
            $("#nextPage").click(() => { if ((currentPage * entriesPerPage) < totalEntries) { currentPage++; updateDisplay(); } });

            fetchData();
        });
    </script>
</x-app-layout>
