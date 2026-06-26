@extends('layouts.app')
@section('title', 'Detail Booking')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('customer.bookings.index') }}" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-800">Detail Booking</h1>
            <p class="text-xs text-gray-400 font-mono">{{ $booking->booking_code }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">

            {{-- Status Card --}}
            @php
                $statusConfig = [
                    'pending'     => ['bg-yellow-50 border-yellow-200', 'text-yellow-700', 'fa-clock', 'Menunggu Konfirmasi Admin'],
                    'confirmed'   => ['bg-blue-50 border-blue-200', 'text-blue-700', 'fa-check-circle', 'Booking Dikonfirmasi'],
                    'in_progress' => ['bg-purple-50 border-purple-200', 'text-purple-700', 'fa-spinner', 'Sedang Dipersiapkan'],
                    'completed'   => ['bg-green-50 border-green-200', 'text-green-700', 'fa-check-double', 'Acara Selesai'],
                    'cancelled'   => ['bg-red-50 border-red-200', 'text-red-700', 'fa-times-circle', 'Booking Dibatalkan'],
                ];
                $sc = $statusConfig[$booking->status] ?? ['bg-gray-50 border-gray-200', 'text-gray-600', 'fa-circle', $booking->status];
            @endphp
            <div class="border rounded-xl p-4 {{ $sc[0] }}">
                <div class="flex items-center gap-3">
                    <i class="fas {{ $sc[2] }} text-xl {{ $sc[1] }}"></i>
                    <div>
                        <p class="font-semibold {{ $sc[1] }}">{{ $sc[3] }}</p>
                        @if($booking->admin_notes)
                            <p class="text-xs mt-0.5 {{ $sc[1] }} opacity-80">{{ $booking->admin_notes }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Info Acara --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-700 mb-4">Informasi Acara</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-400 text-xs mb-1">Mempelai Pria</p>
                        <p class="font-semibold text-gray-800">{{ $booking->groom_name }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-400 text-xs mb-1">Mempelai Wanita</p>
                        <p class="font-semibold text-gray-800">{{ $booking->bride_name }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-400 text-xs mb-1">Tanggal Acara</p>
                        <p class="font-semibold text-gray-800">{{ $booking->event_date->format('d F Y') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-400 text-xs mb-1">Jenis Acara</p>
                        <p class="font-semibold text-gray-800">{{ ucfirst(str_replace('_',' ',$booking->event_type)) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                        <p class="text-gray-400 text-xs mb-1">Lokasi</p>
                        <p class="font-semibold text-gray-800">{{ $booking->event_location }}</p>
                    </div>
                </div>
            </div>

            {{-- Progress --}}
            @if($booking->progress->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-700 mb-4">Progress Persiapan</h3>
                <div class="space-y-3">
                    @foreach($booking->progress->sortBy('order') as $prog)
                    @php
                        $pColor = match($prog->status) {
                            'done'        => 'text-green-500 fa-check-circle',
                            'on_progress' => 'text-blue-500 fa-spinner fa-spin',
                            default       => 'text-gray-300 fa-circle',
                        };
                        $pBg = match($prog->status) {
                            'done'        => 'bg-green-50',
                            'on_progress' => 'bg-blue-50',
                            default       => 'bg-gray-50',
                        };
                    @endphp
                    <div class="flex items-center gap-3 p-3 rounded-lg {{ $pBg }}">
                        <i class="fas {{ $pColor }} text-lg w-6 text-center"></i>
                        <div class="flex-1">
                            <p class="font-medium text-sm text-gray-800">{{ $prog->title }}</p>
                            @if($prog->description)
                                <p class="text-xs text-gray-400">{{ $prog->description }}</p>
                            @endif
                        </div>
                        @if($prog->completed_date)
                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($prog->completed_date)->format('d M Y') }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Riwayat Pembayaran --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-700">Riwayat Pembayaran</h3>
                    @if(!in_array($booking->status, ['completed','cancelled']))
                    <a href="{{ route('customer.payments.create', ['booking_id'=>$booking->id]) }}"
                       class="text-xs bg-rose-600 text-white px-3 py-1.5 rounded-lg hover:bg-rose-700 transition">
                        <i class="fas fa-plus mr-1"></i>Upload Bukti
                    </a>
                    @endif
                </div>
                @forelse($booking->payments as $pay)
                @php
                    $pColor = match($pay->status) {
                        'verified' => 'bg-green-100 text-green-700',
                        'rejected' => 'bg-red-100 text-red-700',
                        default    => 'bg-yellow-100 text-yellow-700',
                    };
                @endphp
                <div class="flex justify-between items-center py-3 border-b border-gray-50">
                    <div>
                        <p class="font-medium text-sm text-gray-800">Rp {{ number_format($pay->amount, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400">{{ ucfirst($pay->payment_type) }} • {{ $pay->transfer_date->format('d M Y') }}</p>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full {{ $pColor }}">{{ ucfirst($pay->status) }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">Belum ada pembayaran</p>
                @endforelse
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Ringkasan Pembayaran --}}
            <div class="bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl p-5 text-white">
                <h3 class="font-semibold mb-4">Ringkasan Pembayaran</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-rose-100">Total Tagihan</span>
                        <span class="font-semibold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-rose-100">Sudah Dibayar</span>
                        <span class="font-semibold">Rp {{ number_format($booking->totalPaid(), 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t border-rose-400 pt-2 flex justify-between">
                        <span class="text-rose-100">Sisa</span>
                        <span class="font-bold text-lg">Rp {{ number_format($booking->remainingPayment(), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Paket Dipesan --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-semibold text-gray-700 mb-3">Paket Dipesan</h3>
                @foreach($booking->packages as $bp)
                <div class="flex justify-between py-2 border-b border-gray-50 text-sm">
                    <p class="text-gray-700">{{ $bp->package->name }}</p>
                    <p class="font-semibold text-gray-800">Rp {{ number_format($bp->price_snapshot, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>

            {{-- Tombol Bayar --}}
            @if(!in_array($booking->status, ['completed','cancelled']) && $booking->remainingPayment() > 0)
            <a href="{{ route('customer.payments.create', ['booking_id'=>$booking->id]) }}"
               class="w-full flex items-center justify-center gap-2 bg-rose-600 text-white py-3 rounded-xl font-bold hover:bg-rose-700 transition">
                <i class="fas fa-credit-card"></i>Upload Bukti Bayar
            </a>
            @endif
        </div>
    </div>
</div>
@endsection