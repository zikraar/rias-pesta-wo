@extends('layouts.app')
@section('title', 'Buat Booking')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Buat Booking Baru</h1>
        <p class="text-sm text-gray-500 mt-1">Isi form di bawah untuk memesan layanan Rias Pesta</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('customer.bookings.store') }}" class="space-y-5">
            @csrf

            {{-- Pilih Paket --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    Pilih Paket <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($packages as $pkg)
                    <label class="relative cursor-pointer block">
                        <input type="radio" name="package_id" value="{{ $pkg->id }}"
                               {{ (old('package_id', request('package')) == $pkg->id) ? 'checked' : '' }}
                               class="peer sr-only" required>
                        <div class="border-2 border-gray-100 peer-checked:border-rose-500 peer-checked:bg-rose-50 rounded-xl p-4 pr-12 transition">
                            <p class="font-semibold text-gray-800">{{ $pkg->name }}</p>
                            <p class="text-rose-600 font-bold text-sm mt-0.5">Rp {{ number_format($pkg->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="absolute top-1/2 right-4 -translate-y-1/2 w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-rose-500 peer-checked:bg-rose-500 flex items-center justify-center transition">
                            <i class="fas fa-check text-white text-[10px] hidden peer-checked:block"></i>
                        </div>
                    </label>
                    @endforeach
                </div>
                <p class="text-xs text-gray-400 mt-2">Pilih salah satu paket layanan.</p>
                @error('package_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="border-t border-gray-100 pt-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Kontak --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Akun</label>
                    <input type="email" value="{{ auth()->user()->email }}" readonly disabled
                           class="w-full border border-gray-200 bg-gray-50 text-gray-500 rounded-lg px-4 py-2.5 text-sm focus:outline-none">
                    <p class="text-xs text-gray-400 mt-1">Notifikasi status booking dikirim ke email ini.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP/WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"
                           placeholder="contoh: 081234567890">
                    @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Nama Mempelai --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mempelai Pria <span class="text-red-500">*</span></label>
                    <input type="text" name="groom_name" value="{{ old('groom_name') }}" required
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"
                           placeholder="Nama lengkap mempelai pria">
                    @error('groom_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mempelai Wanita <span class="text-red-500">*</span></label>
                    <input type="text" name="bride_name" value="{{ old('bride_name') }}" required
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"
                           placeholder="Nama lengkap mempelai wanita">
                    @error('bride_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Tanggal & Jenis Acara --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Acara <span class="text-red-500">*</span></label>
                    <input type="date" name="event_date" value="{{ old('event_date') }}" required
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                    @error('event_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Acara <span class="text-red-500">*</span></label>
                    <select name="event_type" required
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="akad"         {{ old('event_type')=='akad'         ? 'selected' : '' }}>Akad Nikah</option>
                        <option value="resepsi"      {{ old('event_type')=='resepsi'      ? 'selected' : '' }}>Resepsi</option>
                        <option value="akad_resepsi" {{ old('event_type')=='akad_resepsi' ? 'selected' : '' }}>Akad + Resepsi</option>
                    </select>
                    @error('event_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Lokasi & Tamu --}}
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Acara <span class="text-red-500">*</span></label>
                    <input type="text" name="event_location" value="{{ old('event_location') }}" required
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"
                           placeholder="Alamat lengkap lokasi acara">
                    @error('event_location')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Perkiraan Jumlah Tamu <span class="text-red-500">*</span></label>
                    <input type="number" name="guest_count" value="{{ old('guest_count') }}" required min="1"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"
                           placeholder="contoh: 200">
                    @error('guest_count')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Permintaan Khusus --}}
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Permintaan Khusus</label>
                    <textarea name="special_requests" rows="3"
                              class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"
                              placeholder="Ceritakan keinginan khusus Anda (opsional)...">{{ old('special_requests') }}</textarea>
                </div>
            </div>

            {{-- Total Harga Preview --}}
            <div id="pricePreview" class="hidden bg-rose-50 border border-rose-100 rounded-xl p-4">
                <div class="flex justify-between items-center">
                    <p class="text-sm font-semibold text-gray-700">Estimasi Total:</p>
                    <p id="totalPrice" class="text-xl font-bold text-rose-600">Rp 0</p>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-rose-600 text-white py-3 rounded-xl font-bold hover:bg-rose-700 transition">
                    <i class="fas fa-calendar-check mr-2"></i>Buat Booking
                </button>
                <a href="{{ route('customer.dashboard') }}"
                   class="border border-gray-200 text-gray-600 px-6 py-3 rounded-xl text-sm hover:bg-gray-50 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const prices = {
    @foreach($packages as $pkg)
    {{ $pkg->id }}: {{ $pkg->price }},
    @endforeach
};

function updateTotal() {
    const checked = document.querySelector('input[name="package_id"]:checked');
    const preview = document.getElementById('pricePreview');
    const totalEl = document.getElementById('totalPrice');

    if (checked && prices[checked.value]) {
        preview.classList.remove('hidden');
        totalEl.textContent = 'Rp ' + prices[checked.value].toLocaleString('id-ID');
    } else {
        preview.classList.add('hidden');
    }
}

document.querySelectorAll('input[name="package_id"]').forEach(radio => {
    radio.addEventListener('change', updateTotal);
});
updateTotal();
</script>
@endpush