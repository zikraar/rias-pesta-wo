@extends('layouts.admin')
@section('title', 'Laporan')
@section('page-title', 'Laporan & Statistik')
@section('page-subtitle', 'Rekap data booking dan pendapatan')

@section('content')
{{-- Filter Tanggal --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-3 items-end">
        <div>
            <label class="block text-xs text-gray-500 font-medium mb-1">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                   class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
        </div>
        <div>
            <label class="block text-xs text-gray-500 font-medium mb-1">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                   class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
        </div>
        <button type="submit" class="bg-rose-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-rose-700 transition">
            <i class="fas fa-search mr-1"></i> Tampilkan
        </button>
        <a href="{{ route('admin.reports.pdf', ['start_date' => request('start_date', $startDate->format('Y-m-d')), 'end_date' => request('end_date', $endDate->format('Y-m-d'))]) }}"
           target="_blank"
           class="bg-gray-800 text-white px-5 py-2 rounded-lg text-sm hover:bg-gray-700 transition flex items-center gap-2">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </form>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Total Booking</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $summary['total_bookings'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Dikonfirmasi</p>
        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $summary['total_confirmed'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Selesai</p>
        <p class="text-3xl font-bold text-green-600 mt-1">{{ $summary['total_completed'] }}</p>
    </div>
    <div class="bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl p-5 shadow-sm text-white">
        <p class="text-xs text-rose-100 uppercase tracking-wider font-medium">Total Pendapatan</p>
        <p class="text-xl font-bold mt-1">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
    </div>
</div>

{{-- Tabel Booking --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-semibold text-gray-700">
            Detail Booking
            <span class="text-xs text-gray-400 font-normal ml-2">
                {{ $startDate->format('d M Y') }} — {{ $endDate->format('d M Y') }}
            </span>
        </h3>
        <p class="text-sm text-gray-500">{{ $bookings->count() }} data</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">No</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Kode</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Pengantin</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Tgl Acara</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Total</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Dibayar</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($bookings as $i => $booking)
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
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 text-gray-400 text-xs">{{ $i + 1 }}</td>
                    <td class="px-5 py-3">
                        <a href="{{ route('admin.bookings.show', $booking) }}"
                           class="font-mono text-xs text-blue-600 hover:underline">{{ $booking->booking_code }}</a>
                    </td>
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-800">{{ $booking->groom_name }} & {{ $booking->bride_name }}</p>
                        <p class="text-xs text-gray-400">{{ $booking->user->name }}</p>
                    </td>
                    <td class="px-5 py-3 text-gray-600 text-xs">{{ $booking->event_date->format('d M Y') }}</td>
                    <td class="px-5 py-3 font-semibold text-gray-800">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 font-semibold text-green-600">Rp {{ number_format($booking->totalPaid(), 0, ',', '.') }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $statusConfig[$booking->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $statusLabel[$booking->status] ?? $booking->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                        <i class="fas fa-chart-bar text-4xl mb-3 block"></i>
                        Tidak ada data pada periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection