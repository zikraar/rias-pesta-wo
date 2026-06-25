@extends('layouts.admin')
@section('title', 'Detail Pembayaran')
@section('page-title', 'Detail Pembayaran')
@section('page-subtitle', 'Verifikasi atau tolak bukti pembayaran')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Kiri: Bukti Transfer --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <span class="font-mono text-sm bg-gray-100 text-gray-600 px-3 py-1 rounded">{{ $payment->payment_code }}</span>
                    <h2 class="text-xl font-bold text-gray-800 mt-2">Rp {{ number_format($payment->amount, 0, ',', '.') }}</h2>
                    <p class="text-sm text-gray-500">{{ ucfirst(str_replace('_',' ',$payment->payment_type)) }} • {{ $payment->transfer_date->format('d F Y') }}</p>
                </div>
                @php
                    $payColor = match($payment->status) {
                        'verified' => 'bg-green-100 text-green-700',
                        'rejected' => 'bg-red-100 text-red-700',
                        default    => 'bg-yellow-100 text-yellow-700',
                    };
                    $payLabel = match($payment->status) {
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                        default    => 'Menunggu Verifikasi',
                    };
                @endphp
                <span class="text-sm px-3 py-1.5 rounded-full font-semibold {{ $payColor }}">{{ $payLabel }}</span>
            </div>

            {{-- Bukti Transfer --}}
            <div class="mb-6">
                <p class="text-sm font-semibold text-gray-700 mb-3">Bukti Transfer</p>
                @if($payment->proof_image)
                    <img src="{{ asset('storage/'.$payment->proof_image) }}"
                         alt="Bukti Transfer"
                         class="w-full max-w-md rounded-xl border border-gray-200 shadow-sm cursor-pointer hover:opacity-90 transition"
                         onclick="document.getElementById('imgModal').classList.remove('hidden')">
                    {{-- Modal Preview --}}
                    <div id="imgModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4"
                         onclick="this.classList.add('hidden')">
                        <img src="{{ asset('storage/'.$payment->proof_image) }}" class="max-w-full max-h-screen rounded-xl">
                    </div>
                @else
                    <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl p-8 text-center text-gray-400">
                        <i class="fas fa-image text-4xl mb-2"></i>
                        <p class="text-sm">Tidak ada bukti transfer</p>
                    </div>
                @endif
            </div>

            {{-- Info Transfer --}}
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-gray-400 text-xs mb-1">Bank Tujuan</p>
                    <p class="font-semibold text-gray-800">{{ $payment->bank_name ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-gray-400 text-xs mb-1">No. Rekening</p>
                    <p class="font-semibold text-gray-800">{{ $payment->account_number ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-gray-400 text-xs mb-1">Nama Pengirim</p>
                    <p class="font-semibold text-gray-800">{{ $payment->sender_name ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-gray-400 text-xs mb-1">Tgl Transfer</p>
                    <p class="font-semibold text-gray-800">{{ $payment->transfer_date->format('d M Y') }}</p>
                </div>
            </div>

            @if($payment->notes)
            <div class="mt-4 bg-blue-50 border border-blue-100 rounded-lg p-3">
                <p class="text-xs text-blue-600 font-semibold mb-1">Catatan dari Customer</p>
                <p class="text-sm text-gray-700">{{ $payment->notes }}</p>
            </div>
            @endif

            @if($payment->admin_notes)
            <div class="mt-4 bg-orange-50 border border-orange-100 rounded-lg p-3">
                <p class="text-xs text-orange-600 font-semibold mb-1">Catatan Admin</p>
                <p class="text-sm text-gray-700">{{ $payment->admin_notes }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Kanan: Aksi --}}
    <div class="space-y-6">

        {{-- Tombol Verifikasi --}}
        @if($payment->status === 'pending')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 mb-4">Tindakan</h3>
            <div class="space-y-3">
                {{-- Verifikasi --}}
                <form method="POST" action="{{ route('admin.payments.verify', $payment) }}">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Verifikasi pembayaran ini?')"
                            class="w-full bg-green-600 text-white py-3 rounded-lg text-sm font-semibold hover:bg-green-700 transition">
                        <i class="fas fa-check-circle mr-2"></i>Verifikasi Pembayaran
                    </button>
                </form>

                {{-- Tolak --}}
                <button onclick="document.getElementById('rejectForm').classList.toggle('hidden')"
                        class="w-full border-2 border-red-200 text-red-600 py-3 rounded-lg text-sm font-semibold hover:bg-red-50 transition">
                    <i class="fas fa-times-circle mr-2"></i>Tolak Pembayaran
                </button>

                <div id="rejectForm" class="hidden">
                    <form method="POST" action="{{ route('admin.payments.reject', $payment) }}">
                        @csrf
                        <textarea name="admin_notes" rows="3" required
                                  placeholder="Alasan penolakan (wajib diisi)..."
                                  class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 mb-2"></textarea>
                        <button type="submit"
                                class="w-full bg-red-600 text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                            Konfirmasi Tolak
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        @if($payment->status === 'verified')
        <div class="bg-green-50 border border-green-200 rounded-xl p-5 text-center">
            <i class="fas fa-check-circle text-green-500 text-3xl mb-2"></i>
            <p class="font-semibold text-green-700">Pembayaran Terverifikasi</p>
            <p class="text-xs text-green-600 mt-1">{{ $payment->verified_at ? \Carbon\Carbon::parse($payment->verified_at)->format('d M Y H:i') : '' }}</p>
        </div>
        @endif

        {{-- Info Booking --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 mb-4">Info Booking</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-400 text-xs">Kode Booking</p>
                    <a href="{{ route('admin.bookings.show', $payment->booking) }}"
                       class="font-mono text-blue-600 hover:underline">{{ $payment->booking->booking_code }}</a>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Pengantin</p>
                    <p class="font-medium text-gray-800">{{ $payment->booking->groom_name }} & {{ $payment->booking->bride_name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Customer</p>
                    <p class="font-medium text-gray-800">{{ $payment->booking->user->name }}</p>
                </div>
                <div class="border-t pt-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Total Tagihan</span>
                        <span class="font-semibold">Rp {{ number_format($payment->booking->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between mt-1">
                        <span class="text-gray-500">Sudah Dibayar</span>
                        <span class="font-semibold text-green-600">Rp {{ number_format($payment->booking->totalPaid(), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between mt-1">
                        <span class="text-gray-500">Sisa</span>
                        <span class="font-bold text-rose-600">Rp {{ number_format($payment->booking->remainingPayment(), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('admin.payments.index') }}"
           class="w-full flex items-center justify-center gap-2 border border-gray-200 text-gray-600 py-2.5 rounded-lg text-sm hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>
</div>
@endsection