@extends('layouts.app')
@section('title', 'Dashboard Customer')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">

    {{-- Greeting --}}
    <div class="bg-gradient-to-br from-rose-500 to-pink-600 rounded-2xl p-6 text-white mb-8">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-rose-100 text-sm">Selamat datang kembali,</p>
                <h1 class="font-playfair text-2xl font-bold mt-1">{{ auth()->user()->name }}</h1>
                <p class="text-rose-100 text-sm mt-1">
                    <i class="fas fa-calendar-alt mr-1"></i>{{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-white text-2xl"></i>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mb-3">
                <i class="fas fa-calendar-check text-blue-500"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_bookings'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Booking</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center mb-3">
                <i class="fas fa-clock text-yellow-500"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['pending'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Menunggu Konfirmasi</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center mb-3">
                <i class="fas fa-spinner text-purple-500"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['in_progress'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Sedang Diproses</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center mb-3">
                <i class="fas fa-check-circle text-green-500"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['completed'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Selesai</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Booking Terbaru --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b flex justify-between items-center">
                <h3 class="font-semibold text-gray-700">Booking Saya</h3>
                <a href="{{ route('customer.bookings.index') }}" class="text-xs text-rose-600 hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentBookings as $booking)
                @php
                    $statusConfig = [
                        'pending'     => 'bg-yellow-100 text-yellow-700',
                        'confirmed'   => 'bg-blue-100 text-blue-700',
                        'in_progress' => 'bg-purple-100 text-purple-700',
                        'completed'   => 'bg-green-100 text-green-700',
                        'cancelled'   => 'bg-red-100 text-red-700',
                    ];
                    $statusLabel = [
                        'pending'     => 'Menunggu',
                        'confirmed'   => 'Dikonfirmasi',
                        'in_progress' => 'Diproses',
                        'completed'   => 'Selesai',
                        'cancelled'   => 'Dibatalkan',
                    ];
                @endphp
                <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                    <div>
                        <p class="font-medium text-sm text-gray-800">{{ $booking->groom_name }} & {{ $booking->bride_name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            <span class="font-mono">{{ $booking->booking_code }}</span>
                            • {{ $booking->event_date->format('d M Y') }}
                        </p>
                        <p class="text-xs font-semibold text-rose-600 mt-0.5">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $statusConfig[$booking->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $statusLabel[$booking->status] ?? $booking->status }}
                        </span>
                        <a href="{{ route('customer.bookings.show', $booking) }}"
                           class="text-blue-500 hover:text-blue-700 p-1.5 hover:bg-blue-50 rounded-lg transition">
                            <i class="fas fa-eye text-xs"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-10 text-center text-gray-400">
                    <i class="fas fa-calendar-times text-4xl mb-3 block"></i>
                    <p class="text-sm">Belum ada booking</p>
                    <a href="{{ route('customer.bookings.create') }}"
                       class="mt-3 inline-block bg-rose-600 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-rose-700 transition">
                        Buat Booking Sekarang
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Notifikasi --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b">
                    <h3 class="font-semibold text-gray-700">Notifikasi</h3>
                </div>
                <div class="divide-y divide-gray-50 max-h-48 overflow-y-auto">
                    @forelse(auth()->user()->notifications->take(5) as $notif)
                    <div class="p-3 {{ $notif->read_at ? 'opacity-60' : '' }}">
                        <p class="text-xs text-gray-700">{{ $notif->data['message'] ?? '-' }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                    @empty
                    <p class="p-5 text-center text-xs text-gray-400">Tidak ada notifikasi</p>
                    @endforelse
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-semibold text-gray-700 mb-4">Menu Cepat</h3>
                <div class="space-y-2">
                    <a href="{{ route('customer.bookings.create') }}"
                       class="w-full flex items-center gap-3 p-3 bg-rose-50 text-rose-700 rounded-xl hover:bg-rose-100 transition text-sm font-medium">
                        <i class="fas fa-plus-circle w-5 text-center"></i> Buat Booking Baru
                    </a>
                    <a href="{{ route('customer.bookings.index') }}"
                       class="w-full flex items-center gap-3 p-3 bg-gray-50 text-gray-700 rounded-xl hover:bg-gray-100 transition text-sm font-medium">
                        <i class="fas fa-list w-5 text-center"></i> Daftar Booking
                    </a>
                    <a href="{{ route('customer.payments.index') }}"
                       class="w-full flex items-center gap-3 p-3 bg-gray-50 text-gray-700 rounded-xl hover:bg-gray-100 transition text-sm font-medium">
                        <i class="fas fa-credit-card w-5 text-center"></i> Riwayat Pembayaran
                    </a>
                    <a href="{{ route('packages') }}"
                       class="w-full flex items-center gap-3 p-3 bg-gray-50 text-gray-700 rounded-xl hover:bg-gray-100 transition text-sm font-medium">
                        <i class="fas fa-box-open w-5 text-center"></i> Lihat Paket
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection