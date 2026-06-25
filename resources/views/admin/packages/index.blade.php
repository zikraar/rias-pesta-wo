@extends('layouts.admin')
@section('title', 'Paket Layanan')
@section('page-title', 'Paket Layanan')
@section('page-subtitle', 'Kelola paket wedding organizer')

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-sm text-gray-500">Total: <span class="font-semibold text-gray-800">{{ $packages->total() }}</span> paket</p>
    <a href="{{ route('admin.packages.create') }}"
       class="bg-rose-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-rose-700 transition">
        <i class="fas fa-plus mr-2"></i>Tambah Paket
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Paket</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Harga</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($packages as $pkg)
                @php
                    $icons = [
                        'Silver'      => 'fa-medal',
                        'Gold'        => 'fa-award',
                        'Platinum I'  => 'fa-gem',
                        'Platinum II' => 'fa-gem',
                        'Diamond I'   => 'fa-crown',
                        'Diamond II'  => 'fa-crown',
                    ];
                    $icon = $icons[$pkg->name] ?? 'fa-heart';
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-rose-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas {{ $icon }} text-rose-500"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $pkg->name }}</p>
                                @if($pkg->description)
                                    <p class="text-xs text-gray-400 truncate max-w-xs">
                                        {{ Str::limit(str_replace("\n", ' ', $pkg->description), 60) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full capitalize">
                            {{ $pkg->category }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <p class="font-bold text-rose-600">Rp {{ number_format($pkg->price, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $pkg->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                            {{ $pkg->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.packages.edit', $pkg) }}"
                               class="text-blue-500 hover:text-blue-700 p-1.5 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.packages.destroy', $pkg) }}">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Hapus paket {{ addslashes($pkg->name) }}?')"
                                        class="text-red-400 hover:text-red-600 p-1.5 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-12 text-center text-gray-400">
                        <i class="fas fa-box-open text-4xl mb-3 block"></i>
                        Belum ada paket layanan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($packages->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $packages->links() }}</div>
    @endif
</div>
@endsection