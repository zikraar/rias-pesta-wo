@extends('layouts.app')
@section('title', 'Booking Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Booking Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar semua pemesanan Anda</p>
        </div>
        <a href="{{ route('customer.bookings.create') }}"
           class="bg-rose-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-rose-700 transition">
            <i class="fas fa-plus mr-2"></i>Buat Booking Baru
        </a>
    </div>

    <div class="space-y-4">
        @forelse($bookings as $booking)
        @php
            $statusConfig = [
                'pending'     => ['bg-yellow-100 text-yellow-700', 'fa-clock', 'Menunggu Konfirmasi'],
                'confirmed'   => ['bg-blue-100 text-blue-700', 'fa-check-circle', 'Dikonfirmasi'],
                'in_progress' => ['bg-purple-100 text-purple-700', 'fa-spinner', 'Sedang Diproses'],
                'completed'   => ['bg-green-100 text-green-700', 'fa-check-double', 'Selesai'],
                'cancelled'   => ['bg-red-100 text-red-700', 'fa-times-circle', 'Dibatalkan'],
            ];
            $sc = $statusConfig[$booking->status] ?? ['bg-gray-100 text-gray-600', 'fa-circle', $booking->status];
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-heart text-rose-500"></i>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800">{{ $booking->groom_name }} & {{ $booking->bride_name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            <span class="font-mono bg-gray-100 px-1.5 py-0.5 rounded">{{ $booking->booking_code }}</span>
                            <span class="mx-1">•</span>
                            <i class="fas fa-calendar mr-1"></i>{{ $booking->event_date->format('d F Y') }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            <i class="fas fa-map-marker-alt mr-1"></i>{{ $booking->event_location }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3 sm:flex-col sm:items-end">
                    <span class="text-xs px-3 py-1.5 rounded-full font-semibold {{ $sc[0] }}">
                        <i class="fas {{ $sc[1] }} mr-1"></i>{{ $sc[2] }}
                    </span>
                    <p class="font-bold text-rose-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Progress Bar --}}
            @if(in_array($booking->status, ['confirmed','in_progress']))
            @php
                $done  = $booking->progress->where('status','done')->count();
                $total = $booking->progress->count();
                $pct   = $total > 0 ? round(($done/$total)*100) : 0;
            @endphp
            <div class="mt-4 pt-4 border-t border-gray-50">
                <div class="flex justify-between text-xs text-gray-500 mb-1.5">
                    <span>Progress Persiapan</span>
                    <span class="font-semibold text-rose-600">{{ $pct }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-rose-500 h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @endif

            <div class="mt-4 flex gap-2">
                <a href="{{ route('customer.bookings.show', $booking) }}"
                   class="flex-1 text-center bg-rose-50 text-rose-600 py-2 rounded-lg text-sm font-semibold hover:bg-rose-100 transition">
                    <i class="fas fa-eye mr-1"></i>Detail
                </a>
                @if(in_array($booking->status, ['confirmed','in_progress']))
                <a href="{{ route('customer.payments.create', ['booking_id' => $booking->id]) }}"
                   class="flex-1 text-center bg-gray-800 text-white py-2 rounded-lg text-sm font-semibold hover:bg-gray-700 transition">
                    <i class="fas fa-credit-card mr-1"></i>Bayar
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-16 text-center text-gray-400">
            <i class="fas fa-calendar-times text-5xl mb-4 block"></i>
            <p class="font-medium text-gray-600">Belum ada booking</p>
            <p class="text-sm mt-1">Mulai pesan paket pernikahan impian Anda sekarang</p>
            <a href="{{ route('customer.bookings.create') }}"
               class="mt-4 inline-block bg-rose-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-rose-700 transition">
                <i class="fas fa-plus mr-2"></i>Buat Booking Pertama
            </a>
        </div>
        @endforelse
    </div>

    @if($bookings->hasPages())
    <div class="mt-6">{{ $bookings->links() }}</div>
    @endif
</div>
@endsection