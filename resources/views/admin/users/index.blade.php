@extends('layouts.admin')
@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')
@section('page-subtitle', 'Kelola akun admin dan customer')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">No. HP</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Terdaftar</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                @php
                    $roleColor = match($user->role) {
                        'superadmin' => 'bg-purple-100 text-purple-700',
                        'admin'      => 'bg-blue-100 text-blue-700',
                        default      => 'bg-gray-100 text-gray-600',
                    };
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-rose-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-rose-600 font-bold text-xs">{{ strtoupper(substr($user->name,0,1)) }}</span>
                            </div>
                            <p class="font-medium text-gray-800">{{ $user->name }}</p>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-gray-600">{{ $user->email }}</td>
                    <td class="px-5 py-4 text-gray-600">{{ $user->phone ?? '-' }}</td>
                    <td class="px-5 py-4">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $roleColor }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-gray-400 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-4">
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Hapus user {{ addslashes($user->name) }}?')"
                                    class="text-red-400 hover:text-red-600 p-1.5 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </form>
                        @else
                        <span class="text-xs text-gray-300">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                        <i class="fas fa-users text-4xl mb-3 block"></i>
                        Belum ada user
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $users->links() }}</div>
    @endif
</div>
@endsection