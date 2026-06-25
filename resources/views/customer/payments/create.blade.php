@extends('layouts.app')
@section('title', 'Upload Bukti Bayar')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-10">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Upload Bukti Pembayaran</h1>
        <p class="text-sm text-gray-500 mt-1">Booking: <span class="font-mono font-semibold">{{ $booking->booking_code }}</span></p>
    </div>

    {{-- Info Rekening --}}
    <div class="bg-rose-50 border border-rose-100 rounded-xl p-5 mb-6">
        <p class="font-semibold text-rose-700 mb-3"><i class="fas fa-university mr-2"></i>Transfer ke Rekening Berikut:</p>
        <div class="space-y-2">
            @foreach($bankAccounts as $bank)
            <div class="bg-white rounded-lg p-3 flex justify-between items-center">
                <div>
                    <p class="font-bold text-gray-800">{{ $bank['bank'] }}</p>
                    <p class="text-sm font-mono text-gray-600">{{ $bank['number'] }}</p>
                    <p class="text-xs text-gray-400">a.n. {{ $bank['name'] }}</p>
                </div>
                <button onclick="navigator.clipboard.writeText('{{ $bank['number'] }}')"
                        class="text-xs text-rose-600 hover:text-rose-700 border border-rose-200 px-2 py-1 rounded">
                    <i class="fas fa-copy mr-1"></i>Salin
                </button>
            </div>
            @endforeach
        </div>
        <div class="mt-3 bg-white rounded-lg p-3">
            <p class="text-xs text-gray-500">Sisa tagihan yang harus dibayar:</p>
            <p class="text-xl font-bold text-rose-600">Rp {{ number_format($booking->remainingPayment(), 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Form Upload --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('customer.payments.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $booking->id }}">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pembayaran <span class="text-red-500">*</span></label>
                    <select name="payment_type" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                        <option value="dp">DP (Down Payment)</option>
                        <option value="cicilan">Cicilan</option>
                        <option value="pelunasan">Pelunasan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Transfer (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" required min="1"
                           value="{{ old('amount') }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"
                           placeholder="contoh: 5000000">
                    @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pengirim <span class="text-red-500">*</span></label>
                    <input type="text" name="sender_name" required value="{{ old('sender_name', auth()->user()->name) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Transfer <span class="text-red-500">*</span></label>
                    <input type="date" name="transfer_date" required value="{{ old('transfer_date', date('Y-m-d')) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bank Asal</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"
                           placeholder="contoh: BCA">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Rekening Pengirim</label>
                    <input type="text" name="account_number" value="{{ old('account_number') }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"
                           placeholder="contoh: 1234567890">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Transfer <span class="text-red-500">*</span></label>
                <input type="file" name="proof_image" accept="image/*" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none"
                       onchange="previewBukti(this)">
                <img id="previewBukti" src="" alt="" class="hidden mt-3 h-48 rounded-xl object-contain border border-gray-200 w-full">
                @error('proof_image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                <textarea name="notes" rows="2"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"
                          placeholder="Catatan tambahan untuk admin...">{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="flex-1 bg-rose-600 text-white py-3 rounded-xl font-bold hover:bg-rose-700 transition">
                    <i class="fas fa-upload mr-2"></i>Upload Bukti Bayar
                </button>
                <a href="{{ route('customer.bookings.show', $booking) }}"
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
function previewBukti(input) {
    const preview = document.getElementById('previewBukti');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush