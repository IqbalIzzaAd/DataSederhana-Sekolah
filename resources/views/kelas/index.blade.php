<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Kelas</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <a href="{{ route('kelas.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Kelas</a>
            <table class="w-full mt-4 border text-center">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-4 py-2">Nama Kelas</th>
                        <th class="border px-4 py-2">Jumlah Siswa</th>
                        <th class="border px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kelas as $k)
                        <tr>
                            <td class="border px-4 py-2">{{ $k->nama }}</td>
                            <td class="border px-4 py-2">{{ $k->siswas->count() }}</td>
                            <td class="border px-4 py-2">
                                <a href="{{ route('kelas.show', $k->id) }}" class="bg-green-500 text-white px-2 py-1 rounded">Detail</a>
                                <a href="{{ route('kelas.edit', $k->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                                <form action="{{ route('kelas.destroy', $k->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
