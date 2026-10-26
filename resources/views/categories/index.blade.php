@extends('layouts.app')

@section('title', 'Kategori Produk Kopi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center space-x-2 text-xs font-semibold text-coffee-700 bg-coffee-100 px-3 py-1 rounded-full mb-1">
                <i class="fa-solid fa-tags"></i>
                <span>Master Data</span>
            </div>
            <h1 class="text-2xl font-bold text-coffee-950 font-serif">Kategori Produk Kopi</h1>
            <p class="text-xs text-stone-500 mt-1">Klasifikasi produk roastery, biji kopi specialty, minuman, dan alat seduh</p>
        </div>

        @if(auth()->user()->isAdmin())
            <a href="{{ route('categories.create') }}" class="px-4 py-2.5 bg-coffee-800 hover:bg-coffee-900 text-white text-xs font-semibold rounded-xl shadow-sm transition inline-flex items-center space-x-2">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Tambah Kategori Baru</span>
            </a>
        @endif
    </div>

    <!-- Search Form -->
    <div class="bg-white p-4 rounded-2xl border border-stone-200 shadow-sm">
        <form method="GET" action="{{ route('categories.index') }}" class="flex items-center gap-3">
            <div class="relative flex-grow">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-stone-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau deskripsi kategori..."
                       class="w-full pl-9 pr-4 py-2 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
            </div>
            <button type="submit" class="px-4 py-2 bg-stone-800 hover:bg-stone-900 text-white text-xs font-semibold rounded-xl transition">
                Cari
            </button>
            @if($search)
                <a href="{{ route('categories.index') }}" class="px-3 py-2 text-stone-500 hover:text-stone-800 text-xs">Reset</a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-100">
                    <tr>
                        <th class="py-3 px-6">ID</th>
                        <th class="py-3 px-6">Nama Kategori</th>
                        <th class="py-3 px-6">Slug URL</th>
                        <th class="py-3 px-6">Deskripsi</th>
                        <th class="py-3 px-6 text-center">Jumlah Produk</th>
                        @if(auth()->user()->isAdmin())
                            <th class="py-3 px-6 text-right">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-700">
                    @forelse($categories as $category)
                        <tr class="hover:bg-stone-50/50 transition">
                            <td class="py-3.5 px-6 font-mono text-stone-400">{{ $category->id }}</td>
                            <td class="py-3.5 px-6 font-bold text-coffee-950">{{ $category->name }}</td>
                            <td class="py-3.5 px-6 font-mono text-stone-500 text-[11px]">{{ $category->slug }}</td>
                            <td class="py-3.5 px-6 text-stone-500 max-w-xs truncate">{{ $category->description ?? '-' }}</td>
                            <td class="py-3.5 px-6 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-coffee-100 text-coffee-800">
                                    {{ $category->products_count }} Produk
                                </span>
                            </td>
                            @if(auth()->user()->isAdmin())
                                <td class="py-3.5 px-6 text-right space-x-2">
                                    <a href="{{ route('categories.edit', $category) }}" class="text-stone-600 hover:text-coffee-800 font-semibold">
                                        <i class="fa-regular fa-pen-to-square"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-semibold ml-2">
                                            <i class="fa-regular fa-trash-can"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-stone-400">Belum ada kategori yang ditambahkan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="p-4 border-t border-stone-100">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
