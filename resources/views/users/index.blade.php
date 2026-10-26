@extends('layouts.app')

@section('title', 'Kelola Pengguna Sistem')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center space-x-2 text-xs font-semibold text-coffee-700 bg-coffee-100 px-3 py-1 rounded-full mb-1">
                <i class="fa-solid fa-users"></i>
                <span>Manajemen Hak Akses & Role</span>
            </div>
            <h1 class="text-2xl font-bold text-coffee-950 font-serif">Kelola Pengguna Sistem</h1>
            <p class="text-xs text-stone-500 mt-1">Pengaturan akun Admin/Owner, Staff Kasir/Gudang, dan Customer Terdaftar</p>
        </div>

        <a href="{{ route('users.create') }}" class="px-4 py-2.5 bg-coffee-800 hover:bg-coffee-900 text-white text-xs font-semibold rounded-xl shadow-sm transition inline-flex items-center space-x-2">
            <i class="fa-solid fa-user-plus text-[10px]"></i>
            <span>Tambah Pengguna Baru</span>
        </a>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white p-4 rounded-2xl border border-stone-200 shadow-sm">
        <form method="GET" action="{{ route('users.index') }}" class="flex items-center gap-3">
            <div class="relative flex-grow">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-stone-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau email..."
                       class="w-full pl-9 pr-4 py-2 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
            </div>

            <div class="w-44">
                <select name="role" class="w-full px-3 py-2 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin / Owner</option>
                    <option value="staff" {{ $role === 'staff' ? 'selected' : '' }}>Staff Kasir</option>
                    <option value="customer" {{ $role === 'customer' ? 'selected' : '' }}>Customer</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-stone-800 hover:bg-stone-900 text-white text-xs font-semibold rounded-xl transition">
                Cari
            </button>
            @if($search || $role)
                <a href="{{ route('users.index') }}" class="px-3 py-2 text-stone-500 hover:text-stone-800 text-xs">Reset</a>
            @endif
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-100">
                    <tr>
                        <th class="py-3 px-6">ID</th>
                        <th class="py-3 px-6">Nama Pengguna</th>
                        <th class="py-3 px-6">Alamat Email</th>
                        <th class="py-3 px-6 text-center">Hak Akses (Role)</th>
                        <th class="py-3 px-6">Nomor Telepon</th>
                        <th class="py-3 px-6">Terdaftar Sejak</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-700">
                    @forelse($users as $u)
                        <tr class="hover:bg-stone-50/50 transition">
                            <td class="py-3.5 px-6 font-mono text-stone-400">{{ $u->id }}</td>
                            <td class="py-3.5 px-6 font-bold text-coffee-950">{{ $u->name }}</td>
                            <td class="py-3.5 px-6 text-stone-600 font-mono">{{ $u->email }}</td>
                            <td class="py-3.5 px-6 text-center">
                                @php
                                    $badge = match($u->role) {
                                        'admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'staff' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        default => 'bg-amber-100 text-amber-800 border-amber-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badge }}">
                                    {{ ucfirst($u->role) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-stone-500">{{ $u->phone ?? '-' }}</td>
                            <td class="py-3.5 px-6 text-stone-500">{{ $u->created_at->format('d M Y') }}</td>
                            <td class="py-3.5 px-6 text-right space-x-2">
                                <a href="{{ route('users.edit', $u) }}" class="text-stone-600 hover:text-coffee-800 font-semibold">
                                    <i class="fa-regular fa-pen-to-square"></i> Edit
                                </a>
                                @if($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('users.destroy', $u) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-semibold ml-2">
                                            <i class="fa-regular fa-trash-can"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-stone-400">Tidak ada pengguna ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-stone-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
