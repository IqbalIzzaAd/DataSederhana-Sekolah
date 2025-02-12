<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Siswa</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
            @csrf @method('PUT')
            <label class="block">Nama Siswa:</label>
            <input type="text" name="nama" value="{{ $siswa->nama }}" class="w-full border p-2 rounded">

            <label class="block mt-4">Pilih Kelas:</label>
            <select name="kelas_id" class="w-full border p-2 rounded">
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}" @if($siswa->kelas_id == $k->id) selected @endif>{{ $k->nama }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Update</button>
        </form>
    </div>
</x-app-layout>
