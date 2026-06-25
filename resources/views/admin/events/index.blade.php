@extends('layouts.admin')
@section('title', 'Kalender Event')
@section('page-title', 'Kalender Event')
@section('page-subtitle', 'Jadwal dan manajemen acara pernikahan')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
@endpush

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

    {{-- Kalender --}}
    <div class="lg:col-span-3 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div id="calendar"></div>
    </div>

    {{-- Sidebar: Form Tambah & Daftar Event --}}
    <div class="space-y-6">

        {{-- Form Tambah Event --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-4">
                <i class="fas fa-plus-circle text-rose-500 mr-2"></i>Tambah Event
            </h3>
            <form method="POST" action="{{ route('admin.events.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="text-xs text-gray-500 font-medium">Judul Event</label>
                    <input type="text" name="title" required
                           class="w-full mt-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"
                           placeholder="Nama acara...">
                </div>
                <div>
                    <label class="text-xs text-gray-500 font-medium">Tanggal</label>
                    <input type="date" name="event_date" required
                           class="w-full mt-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                </div>
                <div>
                    <label class="text-xs text-gray-500 font-medium">Lokasi</label>
                    <input type="text" name="location"
                           class="w-full mt-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"
                           placeholder="Lokasi acara...">
                </div>
                <div>
                    <label class="text-xs text-gray-500 font-medium">Jenis</label>
                    <select name="type" class="w-full mt-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                        <option value="wedding">Pernikahan</option>
                        <option value="fitting">Fitting</option>
                        <option value="survey">Survey Lokasi</option>
                        <option value="meeting">Meeting</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs text-gray-500 font-medium">Warna</label>
                    <input type="color" name="color" value="#e11d48"
                           class="w-full mt-1 h-9 border border-gray-200 rounded-lg px-2 cursor-pointer">
                </div>
                <button type="submit"
                        class="w-full bg-rose-600 text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-rose-700 transition">
                    <i class="fas fa-plus mr-1"></i> Tambah Event
                </button>
            </form>
        </div>

        {{-- Daftar Event Mendatang --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-700">Event Mendatang</h3>
            </div>
            <div class="divide-y divide-gray-50 max-h-80 overflow-y-auto">
                @forelse($events->where('event_date', '>=', today())->sortBy('event_date')->take(10) as $event)
                <div class="p-4 flex items-start gap-3 hover:bg-gray-50">
                    <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0"
                         style="background-color: {{ $event->color ?? '#e11d48' }}"></div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-sm text-gray-800 truncate">{{ $event->title }}</p>
                        <p class="text-xs text-gray-400">
                            {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                        </p>
                        @if($event->location)
                            <p class="text-xs text-gray-400 truncate">
                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $event->location }}
                            </p>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('admin.events.destroy', $event) }}" class="flex-shrink-0">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus event ini?')"
                                class="text-red-400 hover:text-red-600 p-1 transition">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
                @empty
                <p class="p-6 text-center text-sm text-gray-400">Tidak ada event mendatang</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const events = [
        @foreach($events as $e)
        {
            title: "{{ addslashes($e->title) }}",
            start: "{{ \Carbon\Carbon::parse($e->event_date)->format('Y-m-d') }}",
            color: "{{ $e->color ?? '#e11d48' }}",
            extendedProps: {
                location: "{{ addslashes($e->location ?? '') }}",
                type: "{{ $e->type }}"
            }
        },
        @endforeach
    ];

    const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        locale: 'id',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listWeek'
        },
        events: events,
        eventClick: function(info) {
            const loc = info.event.extendedProps.location;
            const title = info.event.title.replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>');
            alert(title + (loc ? '\nLokasi: ' + loc : ''));
        },
        height: 'auto',
        buttonText: {
            today: 'Hari Ini',
            month: 'Bulan',
            list:  'Daftar',
        },
        eventDisplay: 'block',
        dayMaxEvents: 3,
    });

    calendar.render();
});
</script>
@endpush