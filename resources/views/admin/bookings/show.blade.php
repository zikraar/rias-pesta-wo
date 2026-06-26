@extends('layouts.admin')
@section('title', 'Detail Booking')
@section('page-title', 'Detail Booking')
@section('page-subtitle', 'Kelola dan pantau pesanan')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Kiri: Info Booking --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Info Utama --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-start mb-5">
                <div>
                    <span class="font-mono text-sm bg-gray-100 text-gray-600 px-3 py-1 rounded">{{ $booking->booking_code }}</span>
                    <h2 class="text-xl font-bold text-gray-800 mt-2">{{ $booking->groom_name }} & {{ $booking->bride_name }}</h2>
                    <p class="text-sm text-gray-500">Dipesan oleh: {{ $booking->user->name }} ({{ $booking->user->email }})</p>
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
                <span class="text-sm px-3 py-1.5 rounded-full font-semibold {{ $statusConfig[$booking->status] ?? 'bg-gray-100' }}">
                    {{ $statusLabel[$booking->status] ?? $booking->status }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-gray-400 text-xs mb-1">Tanggal Acara</p>
                    <p class="font-semibold text-gray-800">{{ $booking->event_date->format('d F Y') }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-gray-400 text-xs mb-1">Jenis Acara</p>
                    <p class="font-semibold text-gray-800">{{ ucfirst(str_replace('_',' ',$booking->event_type)) }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-gray-400 text-xs mb-1">Lokasi</p>
                    <p class="font-semibold text-gray-800">{{ $booking->event_location }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-gray-400 text-xs mb-1">Jumlah Tamu</p>
                    <p class="font-semibold text-gray-800">{{ number_format($booking->guest_count) }} orang</p>
                </div>
            </div>

            @if($booking->special_requests)
            <div class="mt-4 bg-yellow-50 border border-yellow-100 rounded-lg p-3">
                <p class="text-xs text-yellow-600 font-semibold mb-1"><i class="fas fa-star mr-1"></i>Permintaan Khusus</p>
                <p class="text-sm text-gray-700">{{ $booking->special_requests }}</p>
            </div>
            @endif
        </div>

        {{-- Paket Dipesan --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 mb-4">Paket yang Dipesan</h3>
            <div class="space-y-3">
                @foreach($booking->packages as $bp)
                <div class="flex justify-between items-center py-3 border-b border-gray-50">
                    <div>
                        <p class="font-medium text-gray-800">{{ $bp->package->name }}</p>
                        <p class="text-xs text-gray-400">{{ ucfirst($bp->package->category) }}</p>
                    </div>
                    <p class="font-semibold text-gray-800">Rp {{ number_format($bp->price_snapshot, 0, ',', '.') }}</p>
                </div>
                @endforeach
                <div class="flex justify-between items-center pt-2">
                    <p class="font-bold text-gray-800">Total</p>
                    <p class="font-bold text-xl text-rose-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Progress --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-700">Progress Persiapan</h3>
            </div>
            @if($booking->progress->count() > 0)
            <div class="space-y-3">
                @foreach($booking->progress->sortBy('order') as $prog)
                @php
                    $progColor = match($prog->status) {
                        'done'        => 'bg-green-100 text-green-700',
                        'on_progress' => 'bg-blue-100 text-blue-700',
                        default       => 'bg-gray-100 text-gray-500',
                    };
                    $progIcon = match($prog->status) {
                        'done'        => 'fa-check-circle text-green-500',
                        'on_progress' => 'fa-spinner text-blue-500',
                        default       => 'fa-circle text-gray-300',
                    };
                @endphp
                <div class="flex items-center gap-4 p-3 rounded-lg hover:bg-gray-50">
                    <i class="fas {{ $progIcon }} text-lg w-6 text-center"></i>
                    <div class="flex-1">
                        <p class="font-medium text-sm text-gray-800">{{ $prog->title }}</p>
                        @if($prog->description)
                            <p class="text-xs text-gray-400">{{ $prog->description }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs px-2 py-1 rounded-full {{ $progColor }}">
                            {{ ucfirst(str_replace('_',' ',$prog->status)) }}
                        </span>
                        @if(!in_array($booking->status, ['completed', 'cancelled']))
                        {{-- Update Progress Form --}}
                        <form method="POST" action="{{ route('admin.progress.update', $prog) }}" class="inline">
                            @csrf @method('PUT')
                            <select name="status" data-current="{{ $prog->status }}" onchange="confirmProgressRevert(this)"
                                    class="text-xs border border-gray-200 rounded-lg px-2 py-1 focus:outline-none">
                                <option value="pending"     {{ $prog->status=='pending'     ? 'selected' : '' }}>Pending</option>
                                <option value="on_progress" {{ $prog->status=='on_progress' ? 'selected' : '' }}>On Progress</option>
                                <option value="done"        {{ $prog->status=='done'        ? 'selected' : '' }}>Done</option>
                            </select>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
                <p class="text-sm text-gray-400 text-center py-6">
                    <i class="fas fa-info-circle mr-1"></i>
                    Progress akan otomatis dibuat saat booking dikonfirmasi
                </p>
            @endif
        </div>

        {{-- Riwayat Pembayaran --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 mb-4">Riwayat Pembayaran</h3>
            @forelse($booking->payments as $payment)
            @php
                $payColor = match($payment->status) {
                    'verified' => 'bg-green-100 text-green-700',
                    'rejected' => 'bg-red-100 text-red-700',
                    default    => 'bg-yellow-100 text-yellow-700',
                };
            @endphp
            <div class="flex items-center justify-between py-3 border-b border-gray-50">
                <div>
                    <p class="font-medium text-sm text-gray-800">{{ $payment->payment_code }}</p>
                    <p class="text-xs text-gray-400">{{ ucfirst($payment->payment_type) }} • {{ $payment->transfer_date->format('d M Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-800">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $payColor }}">{{ ucfirst($payment->status) }}</span>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">Belum ada pembayaran</p>
            @endforelse
        </div>
    </div>

    {{-- Kanan: Aksi Admin --}}
    <div class="space-y-6">

        {{-- Update Status --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 mb-4">Update Status Booking</h3>
            @php
                $nextStep = [
                    'pending'     => ['value' => 'confirmed',   'label' => 'Konfirmasi Booking'],
                    'confirmed'   => ['value' => 'in_progress', 'label' => 'Mulai Proses'],
                    'in_progress' => ['value' => 'completed',   'label' => 'Tandai Selesai'],
                ][$booking->status] ?? null;
                $canCancel = in_array($booking->status, ['pending', 'confirmed', 'in_progress']);
            @endphp
            @if($nextStep || $canCancel)
            <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
                @csrf @method('PUT')
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-500 font-medium">Catatan Admin</label>
                        <textarea name="admin_notes" rows="3"
                                  class="w-full mt-1 border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"
                                  placeholder="Catatan untuk customer...">{{ $booking->admin_notes }}</textarea>
                    </div>
                    @if($nextStep)
                    <button type="submit" name="status" value="{{ $nextStep['value'] }}"
                            class="w-full bg-rose-600 text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-rose-700 transition">
                        <i class="fas fa-arrow-right mr-2"></i>{{ $nextStep['label'] }}
                    </button>
                    @endif
                    @if($canCancel)
                    <button type="submit" name="status" value="cancelled"
                            onclick="return confirm('Batalkan booking ini? Aksi ini tidak bisa dibatalkan kembali.')"
                            class="w-full border border-red-200 text-red-600 py-2.5 rounded-lg text-sm font-semibold hover:bg-red-50 transition">
                        <i class="fas fa-times mr-2"></i>Batalkan Booking
                    </button>
                    @endif
                </div>
            </form>
            @else
            <p class="text-sm text-gray-400 text-center py-4">
                <i class="fas fa-flag-checkered mr-1"></i>Booking sudah final, tidak ada aksi lebih lanjut.
            </p>
            @endif
        </div>

        {{-- Invoice --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 mb-4">Dokumen</h3>
            <a href="{{ route('admin.bookings.invoice', $booking) }}" target="_blank"
               class="w-full flex items-center justify-center gap-2 bg-rose-50 text-rose-600 border border-rose-200 py-2.5 rounded-lg text-sm font-semibold hover:bg-rose-100 transition">
                <i class="fas fa-file-pdf"></i> Cetak Invoice PDF
            </a>
        </div>

        {{-- Info Customer --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 mb-4">Info Customer</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-rose-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-rose-500 text-xs"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">{{ $booking->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $booking->user->email }}</p>
                    </div>
                </div>
                @if($booking->user->phone)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-phone text-gray-400 text-xs"></i>
                    </div>
                    <p class="text-gray-600">{{ $booking->user->phone }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Ringkasan Pembayaran --}}
        <div class="bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl p-6 text-white">
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

        <a href="{{ route('admin.bookings.index') }}"
           class="w-full flex items-center justify-center gap-2 border border-gray-200 text-gray-600 py-2.5 rounded-lg text-sm hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmProgressRevert(select) {
    const current = select.dataset.current;
    if (current === 'done' && select.value !== 'done') {
        if (!confirm('Status ini akan diubah dari "Selesai" ke status sebelumnya. Customer akan menerima notifikasi perubahan ini. Lanjutkan?')) {
            select.value = current;
            return;
        }
    }
    select.form.submit();
}
</script>
@endpush