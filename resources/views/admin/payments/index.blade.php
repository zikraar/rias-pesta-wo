@extends('layouts.admin')
@section('title', 'Kelola Pembayaran')
@section('page-title', 'Verifikasi Pembayaran')
@section('page-subtitle', 'Daftar semua transaksi pembayaran dari pelanggan')

@section('content')
{{-- Filter --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <select name="status" class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
            <option value="">Semua Status</option>
            <option value="pending"  {{ request('status')=='pending'  ? 'selected' : '' }}>Menunggu Verifikasi</option>
            <option value="verified" {{ request('status')=='verified' ? 'selected' : '' }}>Terverifikasi</option>
            <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Ditolak</option>
        </select>
        <button type="submit" class="bg-rose-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-rose-700 transition">
            <i class="fas fa-filter mr-1"></i> Filter
        </button>
        @if(request('status'))
            <a href="{{ route('admin.payments.index') }}" class="border border-gray-200 text-gray-600 px-5 py-2 rounded-lg text-sm hover:bg-gray-50 transition text-center">Reset</a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode Bayar</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Booking</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tgl Transfer</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($payments as $payment)
                @php
                    $payColor = match($payment->status) {
                        'verified' => 'bg-green-100 text-green-700',
                        'rejected' => 'bg-red-100 text-red-700',
                        default    => 'bg-yellow-100 text-yellow-700',
                    };
                    $payLabel = match($payment->status) {
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                        default    => 'Menunggu',
                    };
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4">
                        <span class="font-mono text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ $payment->payment_code }}</span>
                    </td>
                    <td class="px-5 py-4">
                        <a href="{{ route('admin.bookings.show', $payment->booking) }}"
                           class="text-blue-600 hover:underline font-mono text-xs">
                            {{ $payment->booking->booking_code }}
                        </a>
                    </td>
                    <td class="px-5 py-4">
                        <p class="font-medium text-gray-800">{{ $payment->booking->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $payment->booking->user->email }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded capitalize">
                            {{ str_replace('_', ' ', $payment->payment_type) }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <p class="font-semibold text-gray-800">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-5 py-4 text-gray-600 text-xs">
                        {{ $payment->transfer_date->format('d M Y') }}
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $payColor }}">
                            {{ $payLabel }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <a href="{{ route('admin.payments.show', $payment) }}"
                           class="text-blue-500 hover:text-blue-700 p-1.5 hover:bg-blue-50 rounded-lg transition" title="Detail">
                            <i class="fas fa-eye text-sm"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                        <i class="fas fa-credit-card text-4xl mb-3 block"></i>
                        Belum ada data pembayaran
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $payments->links() }}</div>
    @endif
</div>
@endsection