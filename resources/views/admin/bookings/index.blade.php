@extends('layouts.admin')
@section('title', 'Kelola Booking')
@section('page-title', 'Kelola Booking')
@section('page-subtitle', 'Daftar semua pemesanan dari pelanggan')

@section('content')
{{-- Filter & Search --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari kode booking, nama pengantin..."
               class="flex-1 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
        <select name="status" class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
            <option value="">Semua Status</option>
            <option value="pending"     {{ request('status')=='pending'     ? 'selected' : '' }}>Pending</option>
            <option value="confirmed"   {{ request('status')=='confirmed'   ? 'selected' : '' }}>Dikonfirmasi</option>
            <option value="in_progress" {{ request('status')=='in_progress' ? 'selected' : '' }}>Diproses</option>
            <option value="completed"   {{ request('status')=='completed'   ? 'selected' : '' }}>Selesai</option>
            <option value="cancelled"   {{ request('status')=='cancelled'   ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <button type="submit" class="bg-rose-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-rose-700 transition">
            <i class="fas fa-search mr-1"></i> Cari
        </button>
        @if(request('search') || request('status'))
            <a href="{{ route('admin.bookings.index') }}" class="border border-gray-200 text-gray-600 px-5 py-2 rounded-lg text-sm hover:bg-gray-50 transition text-center">
                Reset
            </a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengantin</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tgl Acara</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($bookings as $booking)
                @php
                    $statusConfig = [
                        'pending'     => 'bg-yellow-100 text-yellow-700',
                        'confirmed'   => 'bg-blue-100 text-blue-700',
                        'in_progress' => 'bg-purple-100 text-purple-700',
                        'completed'   => 'bg-green-100 text-green-700',
                        'cancelled'   => 'bg-red-100 text-red-700',
                    ];
                    $statusLabel = [
                        'pending'     => 'Pending',
                        'confirmed'   => 'Dikonfirmasi',
                        'in_progress' => 'Diproses',
                        'completed'   => 'Selesai',
                        'cancelled'   => 'Dibatalkan',
                    ];
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4">
                        <span class="font-mono text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ $booking->booking_code }}</span>
                    </td>
                    <td class="px-5 py-4">
                        <p class="font-medium text-gray-800">{{ $booking->groom_name }}</p>
                        <p class="text-xs text-gray-400">& {{ $booking->bride_name }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-gray-700">{{ $booking->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $booking->user->email }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-gray-700">{{ $booking->event_date->format('d M Y') }}</p>
                        <p class="text-xs text-gray-400">{{ ucfirst(str_replace('_',' ',$booking->event_type)) }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="font-semibold text-gray-800">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $statusConfig[$booking->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $statusLabel[$booking->status] ?? $booking->status }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.bookings.show', $booking) }}"
                               class="text-blue-500 hover:text-blue-700 p-1.5 hover:bg-blue-50 rounded-lg transition" title="Detail">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('admin.bookings.invoice', $booking) }}" target="_blank"
                               class="text-rose-500 hover:text-rose-700 p-1.5 hover:bg-rose-50 rounded-lg transition" title="Invoice">
                                <i class="fas fa-file-pdf text-sm"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                        <i class="fas fa-calendar-times text-4xl mb-3 block"></i>
                        Belum ada booking
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($bookings->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $bookings->links() }}
    </div>
    @endif
</div>
@endsection