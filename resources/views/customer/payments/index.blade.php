@extends('layouts.app')
@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Riwayat Pembayaran</h1>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Kode</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Booking</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Jumlah</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Tgl Transfer</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($payments as $pay)
                    @php
                        $pColor = match($pay->status) {
                            'verified' => 'bg-green-100 text-green-700',
                            'rejected' => 'bg-red-100 text-red-700',
                            default    => 'bg-yellow-100 text-yellow-700',
                        };
                        $pLabel = match($pay->status) {
                            'verified' => 'Terverifikasi',
                            'rejected' => 'Ditolak',
                            default    => 'Menunggu',
                        };
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4">
                            <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ $pay->payment_code }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <a href="{{ route('customer.bookings.show', $pay->booking) }}"
                               class="text-blue-600 hover:underline font-mono text-xs">{{ $pay->booking->booking_code }}</a>
                        </td>
                        <td class="px-5 py-4 font-semibold text-gray-800">Rp {{ number_format($pay->amount, 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-gray-500 text-xs">{{ $pay->transfer_date->format('d M Y') }}</td>
                        <td class="px-5 py-4">
                            <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $pColor }}">{{ $pLabel }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-gray-400">
                            <i class="fas fa-credit-card text-4xl mb-3 block"></i>
                            Belum ada riwayat pembayaran
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
</div>
@endsection