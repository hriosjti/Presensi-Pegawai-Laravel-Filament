<x-filament::page>
    <div class="space-y-8 p-6">

        {{-- Tombol Cetak --}}
        <div class="flex justify-end">
            <x-filament::button color="primary" onclick="printSection('print-area')">
                Cetak Rekap
            </x-filament::button>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-xl shadow p-6 space-y-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Filter Data Absensi</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="namaPegawai" class="block text-sm font-medium text-gray-700">Nama Pegawai</label>
                    <input wire:model="namaPegawai" id="namaPegawai" type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                        placeholder="Cari nama...">
                </div>

                <div>
                    <label for="pegawai" class="block text-sm font-medium text-gray-700">Pilih Pegawai</label>
                    <select wire:model="pegawaiId" id="pegawai"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <option value="">-- Semua Pegawai --</option>
                        @foreach ($pegawaiList as $pegawai)
                            <option value="{{ $pegawai->id }}">{{ $pegawai->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                    <input wire:model="tanggalMulai" type="date" id="tanggal_mulai"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" />
                </div>

                <div>
                    <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700">Tanggal Sampai</label>
                    <input wire:model="tanggalSampai" type="date" id="tanggal_sampai"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" />
                </div>
            </div>

            <div class="flex justify-end">
                <button wire:click="filterData"
                    class="inline-flex items-center gap-2 rounded-md bg-primary-600 px-5 py-2 text-sm font-medium text-white shadow hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1">
                    Filter
                </button>
            </div>
        </div>


        {{-- Tabel --}}
        <div id="print-area" class="overflow-x-auto bg-white rounded-xl border border-gray-200 shadow">
            <table class="w-full min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Nama</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Total Hari</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-green-600 uppercase tracking-wider">
                            Hadir</th>
                        <th
                            class="px-6 py-3 text-center text-xs font-semibold text-yellow-600 uppercase tracking-wider">
                            Terlambat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($data as $rekap)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @foreach ($rekap->tanggal ?? [] as $tgl)
                                    <div>{{ \Carbon\Carbon::parse($tgl)->translatedFormat('d F Y') }}</div>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800 font-medium">{{ $rekap->pegawai->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm">{{ $rekap->total_hari }}</td>
                            <td class="px-6 py-4 text-center text-green-600 font-semibold text-sm">{{ $rekap->hadir }}
                            </td>
                            <td class="px-6 py-4 text-center text-yellow-600 font-semibold text-sm">
                                {{ $rekap->terlambat }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- Script Print --}}
    <script>
        function printSection(divId) {
            const printContents = document.getElementById(divId).innerHTML;
            const originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload(); // agar kembali normal setelah print
        }
    </script>
</x-filament::page>
