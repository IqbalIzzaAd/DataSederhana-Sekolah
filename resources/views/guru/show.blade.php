<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Detail Guru</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 id="guruNama" class="text-2xl font-bold"></h3>
            <p class="mt-2 text-gray-600">Mengajar di Kelas:</p>
            <ul id="guruKelas"></ul>

            <div class="mt-4">
                <a href="#" id="editGuruBtn" class="bg-yellow-500 text-white px-4 py-2 rounded">Edit</a>
                <a href="{{ route('guru.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
            </div>
        </div>
    </div>

   
    <script>
        function showGuruDetail(guruId) {
            $.ajax({
                url: "/guru/" + guruId,
                method: "GET",
                success: function(response) {
                    $("#modalGuruNama").text(response.nama);
                    $("#guruNama").text(response.nama);
                    
                    $("#modalGuruKelas").empty();
                    $("#guruKelas").empty();

                    response.kelas.forEach(kelas => {
                        $("#modalGuruKelas").append("<li>" + kelas.nama + "</li>");
                        $("#guruKelas").append("<li>" + kelas.nama + "</li>");
                    });

                    $("#modalEditGuruBtn").attr("href", "/guru/" + guruId + "/edit");
                    $("#editGuruBtn").attr("href", "/guru/" + guruId + "/edit");

                    $("#guruModal").removeClass("hidden");
                },
                error: function(xhr) {
                    alert("Gagal mengambil data guru!");
                }
            });
        }

        $(document).ready(function() {
            let guruId = window.location.pathname.split("/").pop();
            showGuruDetail(guruId);

            $("#closeModal").click(function() {
                $("#guruModal").addClass("hidden");
            });
        });
    </script>
</x-app-layout>
