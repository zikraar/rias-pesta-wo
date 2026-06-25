@extends('layouts.app')
@section('title', 'Dashboard Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-rose-600 to-pink-600 rounded-2xl p-6 text-white mb-8">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-2xl font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-rose-100 text-sm">Selamat datang kembali,</p>
                    <h2 class="text-xl font-bold">{{ auth()->user()->name }}</h2>
                </div>
            </div>
            <a href="{{ route('customer.bookings.create') }}"
               class="bg-white text-rose-600 hover:bg-rose-50 font-semibold px-5 py-2.5 rounded-xl text-sm transition">
                <i class="fas fa-plus mr-2"></i>Pesan Sekarang
            </a>
        </div>

        {{-- Mini Stats --}}
        <div class="grid grid-cols-3 gap-4 mt-6">
            <div class="bg-white bg-opacity-20 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold">{{ $stats['total_bookings'] }}</p>
                <p class="text-rose-100 text-xs mt-1">Total Booking</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold">{{ $stats['active_bookings'] }}</p>
                <p class="text-rose-100 text-xs mt-1">Aktif</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold">{{ $stats['completed'] }}</p>
                <p class="text-rose-100 text-xs mt-1">Selesai</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Booking Aktif --}}
        <div class="lg:col-span-2 space-y-4">
            <h3 class="font-semibold text-gray-700">Booking Aktif</h3>
            @forelse($activeBookings as $booking)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $booking->groom_name }} & {{ $booking->bride_name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $booking->booking_code }} • {{ $booking->event_date->format('d F Y') }}</p>
                            </div>
                            @php
                                $cfg = ['pending'=>'bg-yellow-100 text-yellow-700','confirmed'=>'bg-blue-100 text-blue-700','in_progress'=>'bg-purple-100 text-purple-700'];
                                $lbl = ['pending'=>'Menunggu Konfirmasi','confirmed'=>'Dikonfirmasi','in_progress'=>'Sedang Diproses'];
                            @endphp
                            <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $cfg[$booking->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $lbl[$booking->status] ?? $booking->status }}
                            </span>
                        </div>

                        {{-- Progress Bar --}}
                        @if($booking->progress->count() > 0)
                        @php $pct = \App\Models\Progress::getPercentage($booking->id); @endphp
                        <div class="mb-3">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Progress Persiapan</span>
                                <span class="font-medium text-rose-600">{{ $pct }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-rose-500 h-2 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>

                        {{-- Progress Steps Mini --}}
                        <div class="flex gap-1.5 flex-wrap mb-3">
                            @foreach($booking->progress->take(7) as $step)
                                <span title="{{ $step->title }}"
                                      class="w-6 h-6 rounded-full text-xs flex items-center justify-center
                                      {{ $step->status === 'done' ? 'bg-green-500 text-white' : ($step->status === 'on_progress' ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-400') }}">
                                    {{ $loop->iteration }}
                                </span>
                            @endforeach
                        </div>
                        @endif

                        <div class="flex gap-2">
                            <a href="{{ route('customer.bookings.show', $booking) }}"
                               class="flex-1 text-center text-xs bg-rose-50 text-rose-600 hover:bg-rose-100 font-medium py-2 rounded-lg transition">
                                Detail Booking
                            </a>
                            @if(in_array($booking->status, ['confirmed', 'in_progress']) && $booking->remainingPayment() > 0)
                            <a href="{{ route('customer.payments.create', ['booking_id' => $booking->id]) }}"
                               class="flex-1 text-center text-xs bg-green-50 text-green-600 hover:bg-green-100 font-medium py-2 rounded-lg transition">
                                Bayar Sekarang
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <i class="fas fa-calendar-times text-4xl text-gray-200 mb-3"></i>
                    <p class="text-gray-500 font-medium">Belum ada booking aktif</p>
                    <a href="{{ route('customer.bookings.create') }}"
                       class="inline-block mt-4 bg-rose-600 text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-rose-700 transition">
                        Buat Booking Pertama
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Notifikasi --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b">
                    <h3 class="font-semibold text-gray-700 text-sm">Notifikasi Terbaru</h3>
                </div>
                <div class="divide-y max-h-64 overflow-y-auto">
                    @forelse($notifications as $notif)
                        <a href="{{ $notif->data['url'] ?? '#' }}"
                           class="block p-3 hover:bg-gray-50 {{ $notif->read_at ? 'opacity-60' : '' }}">
                            @php $type = $notif->data['type'] ?? ''; @endphp
                            <div class="flex gap-3">
                                <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center
                                    {{ $type === 'payment_verified' ? 'bg-green-100' : ($type === 'payment_rejected' ? 'bg-red-100' : 'bg-blue-100') }}">
                                    <i class="fas {{ $type === 'payment_verified' ? 'fa-check text-green-500' : ($type === 'payment_rejected' ? 'fa-times text-red-500' : 'fa-info text-blue-500') }} text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-700">{{ $notif->data['message'] }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="p-4 text-center text-xs text-gray-400">Tidak ada notifikasi</p>
                    @endforelse
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <h3 class="font-semibold text-gray-700 text-sm mb-3">Menu Cepat</h3>
                <div class="space-y-2">
                    <a href="{{ route('customer.bookings.create') }}"
                       class="flex items-center p-3 rounded-xl hover:bg-rose-50 text-gray-600 hover:text-rose-600 transition text-sm">
                        <i class="fas fa-plus-circle w-5 mr-3 text-rose-400"></i> Buat Booking Baru
                    </a>
                    <a href="{{ route('customer.bookings.index') }}"
                       class="flex items-center p-3 rounded-xl hover:bg-rose-50 text-gray-600 hover:text-rose-600 transition text-sm">
                        <i class="fas fa-list w-5 mr-3 text-rose-400"></i> Riwayat Booking
                    </a>
                    <a href="{{ route('customer.payments.index') }}"
                       class="flex items-center p-3 rounded-xl hover:bg-rose-50 text-gray-600 hover:text-rose-600 transition text-sm">
                        <i class="fas fa-credit-card w-5 mr-3 text-rose-400"></i> Riwayat Pembayaran
                    </a>
                    <a href="{{ route('customer.profile') }}"
                       class="flex items-center p-3 rounded-xl hover:bg-rose-50 text-gray-600 hover:text-rose-600 transition text-sm">
                        <i class="fas fa-user-edit w-5 mr-3 text-rose-400"></i> Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection