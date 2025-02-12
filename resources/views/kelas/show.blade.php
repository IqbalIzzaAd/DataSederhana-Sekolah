<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Detail Kelas</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-2xl font-bold">{{ $kelas->nama }}</h3>
            <p class="mt-2 text-gray-600">Jumlah Siswa: {{ $kelas->siswas->count() }}</p>
            <p class="mt-2 text-gray-600">Wali Kelas: {{ $kelas->guru->nama ?? 'Belum ada wali kelas' }}</p>

            <h4 class="mt-4 text-lg font-semibold">Daftar Siswa:</h4>
            <ul class="mt-2">
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
</x-app-layout>
