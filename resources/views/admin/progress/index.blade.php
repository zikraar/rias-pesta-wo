@extends('layouts.admin')
@section('title', 'Kelola Progress')
@section('page-title', 'Progress Persiapan')
@section('page-subtitle', 'Pantau dan update progress setiap booking')

@section('content')
<div class="space-y-6">
    @forelse($bookings as $booking)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Header Booking --}}
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-heart text-rose-500"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">{{ $booking->groom_name }} & {{ $booking->bride_name }}</p>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="font-mono text-xs text-gray-400">{{ $booking->booking_code }}</span>
                        <span class="text-gray-300">•</span>
                        <span class="text-xs text-gray-400">{{ $booking->user->name }}</span>
                        <span class="text-gray-300">•</span>
                        <span class="text-xs text-gray-400">{{ $booking->event_date->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @php
                    $done  = $booking->progress->where('status','done')->count();
                    $total = $booking->progress->count();
                    $pct   = $total > 0 ? round(($done/$total)*100) : 0;
                @endphp
                <div class="text-right">
                    <p class="text-xs text-gray-400">Progress</p>
                    <p class="font-bold text-rose-600">{{ $pct }}%</p>
                </div>
                <div class="w-24 bg-gray-100 rounded-full h-2">
                    <div class="bg-rose-500 h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>
                <a href="{{ route('admin.bookings.show', $booking) }}"
                   class="text-xs text-blue-500 hover:underline whitespace-nowrap">
                    <i class="fas fa-external-link-alt mr-1"></i>Detail
                </a>
            </div>
        </div>

        {{-- Progress Steps --}}
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($booking->progress->sortBy('order') as $prog)
                @php
                    $cardColor = match($prog->status) {
                        'done'        => 'border-green-200 bg-green-50',
                        'on_progress' => 'border-blue-200 bg-blue-50',
                        default       => 'border-gray-100 bg-gray-50',
                    };
                    $iconColor = match($prog->status) {
                        'done'        => 'text-green-500 fa-check-circle',
                        'on_progress' => 'text-blue-500 fa-spinner fa-spin',
                        default       => 'text-gray-300 fa-circle',
                    };
                    $badgeColor = match($prog->status) {
                        'done'        => 'bg-green-100 text-green-700',
                        'on_progress' => 'bg-blue-100 text-blue-700',
                        default       => 'bg-gray-100 text-gray-500',
                    };
                @endphp
                <div class="border rounded-xl p-4 {{ $cardColor }} transition">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <i class="fas {{ $iconColor }}"></i>
                            <p class="font-medium text-sm text-gray-800">{{ $prog->title }}</p>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $badgeColor }} whitespace-nowrap ml-2">
                            {{ match($prog->status) { 'done' => 'Selesai', 'on_progress' => 'Berlangsung', default => 'Pending' } }}
                        </span>
                    </div>

                    @if($prog->description)
                        <p class="text-xs text-gray-500 mb-2 pl-5">{{ $prog->description }}</p>
                    @endif

                    @if($prog->completed_date)
                        <p class="text-xs text-gray-400 pl-5 mb-2">
                            <i class="fas fa-calendar-check mr-1"></i>
                            {{ \Carbon\Carbon::parse($prog->completed_date)->format('d M Y') }}
                        </p>
                    @endif

                    {{-- Update Status --}}
                    <form method="POST" action="{{ route('admin.progress.update', $prog) }}" class="mt-3 pl-5" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <select name="status" data-current="{{ $prog->status }}" onchange="confirmProgressRevert(this)"
                                class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 bg-white focus:outline-none focus:ring-2 focus:ring-rose-300">
                            <option value="pending"     {{ $prog->status=='pending'     ? 'selected' : '' }}>Pending</option>
                            <option value="on_progress" {{ $prog->status=='on_progress' ? 'selected' : '' }}>On Progress</option>
                            <option value="done"        {{ $prog->status=='done'        ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </form>
                </div>
                @endforeach
            </div>

            @if($booking->progress->count() === 0)
                <p class="text-sm text-gray-400 text-center py-4">
                    <i class="fas fa-info-circle mr-1"></i>
                    Progress akan otomatis dibuat saat status booking diubah ke "Dikonfirmasi"
                </p>
            @endif
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-16 text-center text-gray-400">
        <i class="fas fa-tasks text-5xl mb-4 block"></i>
        <p class="font-medium">Belum ada booking aktif</p>
        <p class="text-sm mt-1">Progress muncul saat ada booking berstatus Dikonfirmasi atau Diproses</p>
    </div>
    @endforelse
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