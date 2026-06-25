@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan data Rias Pesta Pekanbaru')

@section('content')
{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Booking</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_bookings'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar-check text-blue-500 text-xl"></i>
            </div>
        </div>
        <p class="text-xs text-yellow-600 mt-3"><i class="fas fa-clock mr-1"></i>{{ $stats['pending_bookings'] }} menunggu konfirmasi</p>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Dikonfirmasi</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['confirmed'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
        </div>
        <p class="text-xs text-green-600 mt-3"><i class="fas fa-check mr-1"></i>{{ $stats['completed'] }} selesai</p>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Customer</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_customers'] }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-purple-500 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl p-5 shadow-sm text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-rose-100 font-medium uppercase tracking-wider">Revenue Bulan Ini</p>
                <p class="text-2xl font-bold mt-1">Rp {{ number_format($stats['revenue_month'], 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                <i class="fas fa-coins text-white text-xl"></i>
            </div>
        </div>
        @if($stats['pending_payments'] > 0)
            <p class="text-xs text-rose-100 mt-3"><i class="fas fa-exclamation-circle mr-1"></i>{{ $stats['pending_payments'] }} pembayaran menunggu verifikasi</p>
        @endif
    </div>
</div>

{{-- Charts & Tables --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Revenue Chart --}}
    <div class="lg:col-span-2 bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-700 mb-4">Revenue 6 Bulan Terakhir</h3>
        <canvas id="revenueChart" height="100"></canvas>
    </div>

    {{-- Booking Status Pie --}}
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-700 mb-4">Status Booking</h3>
        <canvas id="statusChart" height="180"></canvas>
    </div>
</div>

{{-- Recent Bookings & Upcoming Events --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent Bookings --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-700">Booking Terbaru</h3>
            <a href="{{ route('admin.bookings.index') }}" class="text-xs text-rose-600 hover:underline">Lihat Semua</a>
        </div>
        <div class="divide-y">
            @forelse($recentBookings as $booking)
                <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                    <div>
                        <p class="font-medium text-sm text-gray-800">{{ $booking->groom_name }} & {{ $booking->bride_name }}</p>
                        <p class="text-xs text-gray-400">{{ $booking->booking_code }} • {{ $booking->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $booking->event_date->format('d M Y') }}</p>
                    </div>
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
                    <span class="text-xs px-2 py-1 rounded-full font-medium {{ $statusConfig[$booking->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $statusLabel[$booking->status] ?? $booking->status }}
                    </span>
                </div>
            @empty
                <p class="p-6 text-center text-sm text-gray-400">Belum ada booking</p>
            @endforelse
        </div>
    </div>

    {{-- Upcoming Events --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-700">Event Mendatang (7 Hari)</h3>
            <a href="{{ route('admin.events.index') }}" class="text-xs text-rose-600 hover:underline">Kalender</a>
        </div>
        <div class="divide-y">
            @forelse($upcomingEvents as $event)
                <div class="p-4 flex items-center gap-4">
                    <div class="w-12 h-12 bg-rose-50 rounded-xl flex flex-col items-center justify-center flex-shrink-0">
                        <p class="text-rose-600 font-bold text-sm leading-none">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</p>
                        <p class="text-rose-400 text-xs">{{ \Carbon\Carbon::parse($event->event_date)->format('M') }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-sm text-gray-800">{{ $event->title }}</p>
                        <p class="text-xs text-gray-400">{{ $event->location ?? 'Lokasi belum ditentukan' }}</p>
                    </div>
                </div>
            @empty
                <p class="p-6 text-center text-sm text-gray-400">Tidak ada event dalam 7 hari ke depan</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Revenue Chart
const revenueData = @json($revenueChart);
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: revenueData.map(d => d.month),
        datasets: [{
            label: 'Revenue (Rp)',
            data: revenueData.map(d => d.revenue),
            backgroundColor: 'rgba(225, 29, 72, 0.1)',
            borderColor: 'rgba(225, 29, 72, 0.8)',
            borderWidth: 2,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                ticks: {
                    callback: (v) => 'Rp ' + (v/1000000).toFixed(0) + 'jt',
                    font: { size: 11 }
                }
            }
        }
    }
});

// Status Pie Chart
const statusData = @json($bookingStatus);
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(statusData),
        datasets: [{
            data: Object.values(statusData),
            backgroundColor: ['#fbbf24','#3b82f6','#a855f7','#22c55e','#ef4444'],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 12 } }
        },
        cutout: '65%',
    }
});
</script>
@endpush