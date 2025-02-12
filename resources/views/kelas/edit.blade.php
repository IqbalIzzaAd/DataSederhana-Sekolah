<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Kelas</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
            @csrf @method('PATCH')
            <div class="mb-4">
                <label class="block text-gray-700">Nama Kelas</label>
                <input type="text" name="nama" class="w-full px-4 py-2 border rounded-lg" value="{{ $kelas->nama }}">
            </div>
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
</x-app-layout>
