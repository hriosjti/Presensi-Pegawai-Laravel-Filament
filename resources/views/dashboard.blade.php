<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Dashboard Absensi</h2>
    </x-slot>

    <div class="p-4 max-w-3xl mx-auto space-y-6">
        {{-- Profil Pegawai --}}
        <div
            class="bg-white/90 backdrop-blur-md border border-gray-200 p-6 rounded-xl shadow-md flex gap-6 items-center">
            @if ($pegawai && $pegawai->foto)
                <img src="{{ asset('storage/' . $pegawai->foto) }}" alt="Foto {{ $pegawai->name }}"
                    class="w-24 h-24 rounded-full object-cover shadow-inner">
            @else
                <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-sm">
                    N/A
                </div>
            @endif

            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Profil Pegawai</h3>
                @if ($pegawai)
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li><strong>Nama:</strong> {{ $pegawai->name }}</li>
                        <li><strong>NIP:</strong> {{ $pegawai->nip }}</li>
                        <li><strong>Jabatan:</strong> {{ $pegawai->jabatan }}</li>
                        <li><strong>Unit Kerja:</strong> {{ $pegawai->divisi }}</li>
                    </ul>
                @else
                    <p class="text-red-600">Data pegawai belum terhubung.</p>
                @endif
            </div>
        </div>
        {{-- Notifikasi bukan admin --}}
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error! </strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Notifikasi --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form Absensi --}}
        <form id="formAbsensi" method="POST" action="{{ route('absensi-pegawai.store') }}">
            @csrf
            <input type="hidden" name="aksi" id="aksi">
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">

            <div class="flex justify-between gap-4">
                {{-- Tombol Check In --}}
                @if (!$absensiHariIni || !$absensiHariIni->waktu_checkin)
                    <button type="button" onclick="submitAbsensi('checkin')"
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                        Check In
                    </button>
                @else
                    <button disabled
                        class="flex-1 px-4 py-2 bg-blue-300 text-white font-semibold rounded-lg cursor-not-allowed">
                        Sudah Check In
                    </button>
                @endif

                {{-- Tombol Check Out --}}
                @if (!$absensiHariIni || !$absensiHariIni->waktu_checkout)
                    <button type="button" onclick="submitAbsensi('checkout')"
                        class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow transition">
                        Check Out
                    </button>
                @else
                    <button disabled
                        class="flex-1 px-4 py-2 bg-red-300 text-white font-semibold rounded-lg cursor-not-allowed">
                        Sudah Check Out
                    </button>
                @endif
            </div>
        </form>

        {{-- Riwayat Absensi --}}
        <div class="bg-white/90 backdrop-blur-md border border-gray-200 p-6 rounded-xl shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Absensi Terakhir</h3>

            @if ($riwayat->isEmpty())
                <p class="text-gray-600 text-sm">Belum ada data absensi.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border border-gray-300">
                        <thead class="bg-gray-100 text-left">
                            <tr>
                                <th class="p-2 border">Tanggal</th>
                                <th class="p-2 border">Check In</th>
                                <th class="p-2 border">Check Out</th>
                                <th class="p-2 border">Status</th>
                                <th class="p-2 border">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($riwayat as $absensi)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-2 border">{{ $absensi->tanggal }}</td>
                                    <td class="p-2 border">{{ $absensi->waktu_checkin ?? '-' }}</td>
                                    <td class="p-2 border">{{ $absensi->waktu_checkout ?? '-' }}</td>
                                    <td class="p-2 border">{{ ucfirst($absensi->status) }}</td>
                                    <td class="p-2 border">{{ $absensi->catatan ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- JS Geolocation --}}
    <script>
        function submitAbsensi(action) {
            if (!navigator.geolocation) {
                alert("Geolocation tidak didukung browser.");
                return;
            }

            navigator.geolocation.getCurrentPosition(function(pos) {
                document.getElementById('aksi').value = action;
                document.getElementById('latitude').value = pos.coords.latitude;
                document.getElementById('longitude').value = pos.coords.longitude;
                document.getElementById('formAbsensi').submit();
            }, function() {
                alert("Gagal mendapatkan lokasi. Pastikan izin lokasi aktif.");
            });
        }
    </script>
</x-app-layout>
