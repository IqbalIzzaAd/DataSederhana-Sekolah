<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Detail Siswa</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-2xl font-bold">{{ $siswa->nama }}</h3>
            <p class="mt-2 text-gray-600">Kelas: <span class="font-semibold">{{ $siswa->kelas->nama }}</span></p>

            <div class="mt-4">
                <a href="{{ route('siswa.edit', $siswa->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">Edit</a>
                <a href="{{ route('siswa.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
            </div>
        </div>
    </div>
</x-app-layout>
