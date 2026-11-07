<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Peminjaman - E-Perpus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">
    @role('siswa')
        @include('layouts.nav_siswa')
    @else
        @include('layouts.nav_admin')
    @endrole

    <div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-white">Daftar Peminjaman Buku</h1>
                <p class="text-slate-400 text-sm mt-1">Kelola dan validasi pengajuan peminjaman serta pengembalian buku</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-2xl text-xs font-semibold">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-slate-900/60 backdrop-blur-md border border-slate-800/80 rounded-3xl p-6 shadow-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-800 text-slate-400 text-xs font-bold uppercase tracking-wider">
                            <th class="pb-4 px-4">No</th>
                            <th class="pb-4 px-4">Peminjam</th>
                            <th class="pb-4 px-4">Buku</th>
                            <th class="pb-4 px-4">Tgl Pinjam</th>
                            <th class="pb-4 px-4">Rencana Kembali</th>
                            <th class="pb-4 px-4">Status</th>
                            <th class="pb-4 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-sm">
                        @forelse($peminjamans as $index =>$item)
                            <tr class="hover:bg-slate-800/30 transition">
                                <td class="py-4 px-4 text-slate-500 font-medium">{{ $index + 1 }}</td>
                                <td class="py-4 px-4 font-bold text-white">{{ $item->user->name ?? 'User N/A' }}</td>
                                <td class="py-4 px-4 text-slate-300 font-medium">{{ $item->buku->judul ?? 'Buku N/A' }}</td>
                                <td class="py-4 px-4 text-slate-400 text-xs">{{ $item->tanggal_pinjam }}</td>
                                <td class="py-4 px-4 text-slate-400 text-xs">{{ $item->tanggal_kembali ?? '-' }}</td>
                                <td class="py-4 px-4">
                                    @if($item->status == 'diajukan')
                                        <span class="text-[10px] font-bold uppercase bg-amber-500/10 text-amber-400 border border-amber-500/20 px-2.5 py-1 rounded-full">Diajukan</span>
                                    @elseif($item->status == 'dipinjam')
                                        <span class="text-[10px] font-bold uppercase bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 px-2.5 py-1 rounded-full">Dipinjam</span>
                                    @elseif($item->status == 'menunggu_kembali')
                                        <span class="text-[10px] font-bold uppercase bg-purple-500/10 text-purple-400 border border-purple-500/20 px-2.5 py-1 rounded-full">Menunggu Kembali</span>
                                    @elseif($item->status == 'dikembalikan')
                                        <span class="text-[10px] font-bold uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2.5 py-1 rounded-full">Dikembalikan</span>
                                    @else
                                        <span class="text-[10px] font-bold uppercase bg-rose-500/10 text-rose-400 border border-rose-500/20 px-2.5 py-1 rounded-full">{{ $item->status }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right space-x-1">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('peminjaman.show', $item->id) }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-xl transition">Detail</a>

                                        @role('admin|petugas')
                                            {{-- Form Validasi Pengajuan Pinjam --}}
                                            @if($item->status == 'diajukan')
                                                <form action="{{ route('peminjaman.validasiPinjam', $item->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="action" value="setuju">
                                                    <button type="submit" class="px-3 py-1.5 bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-xs font-semibold rounded-xl hover:bg-emerald-500 hover:text-white transition">Setujui</button>
                                                </form>

                                                <form action="{{ route('peminjaman.validasiPinjam', $item->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="action" value="tolak">
                                                    <button type="submit" class="px-3 py-1.5 bg-rose-500/20 border border-rose-500/30 text-rose-300 text-xs font-semibold rounded-xl hover:bg-rose-500 hover:text-white transition">Tolak</button>
                                                </form>
                                            @endif

                                            {{-- Form Validasi Pengembalian Buku --}}
                                            @if($item->status == 'menunggu_kembali')
                                                <form action="{{ route('peminjaman.validasiKembali', $item->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="px-3 py-1.5 bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 text-xs font-semibold rounded-xl hover:bg-indigo-500 hover:text-white transition">Terima Kembali</button>
                                                </form>
                                            @endif
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-500 text-sm">Belum ada data peminjaman buku.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>