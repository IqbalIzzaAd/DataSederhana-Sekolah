<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Guru</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <form action="{{ route('guru.update', $guru->id) }}" method="POST">
            @csrf @method('PUT')
            <label class="block">Nama Guru:</label>
            <input type="text" name="nama" value="{{ $guru->nama }}" class="w-full border p-2 rounded">

            <label class="block mt-4">Mengajar Kelas:</label>
            <select name="kelas_id[]" multiple class="w-full border p-2 rounded">
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}" @if($guru->kelas->contains($k->id)) selected @endif>{{ $k->nama }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Update</button>
        </form>
    </div>
</x-app-layout>
