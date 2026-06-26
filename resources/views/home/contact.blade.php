@extends('layouts.app')
@section('title', 'Kontak')

@section('content')

{{-- Header --}}
<section class="bg-gradient-to-br from-rose-900 via-rose-700 to-pink-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <span class="inline-block bg-rose-500 bg-opacity-40 text-rose-100 text-xs font-semibold px-3 py-1 rounded-full mb-4 uppercase tracking-wider">
            Hubungi Kami
        </span>
        <h1 class="font-playfair text-4xl lg:text-5xl font-bold mb-4">Kontak Rias Pesta</h1>
        <p class="text-rose-100 text-lg max-w-2xl mx-auto">
            Kami siap membantu mewujudkan pernikahan impian Anda. Jangan ragu untuk menghubungi kami.
        </p>
    </div>
</section>

<section class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Info Kontak --}}
            <div class="space-y-5">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-map-marker-alt text-rose-500 text-xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Alamat</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Jl. Delima Gg Delima VII No. 3<br>Pekanbaru, Riau 28294
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center mb-4">
                        <i class="fab fa-whatsapp text-green-500 text-xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">WhatsApp</h3>
                    <a href="https://wa.me/6281276031567" target="_blank"
                       class="text-green-600 hover:text-green-700 font-medium text-sm">
                        0812-7603-1567
                    </a>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-pink-50 rounded-xl flex items-center justify-center mb-4">
                        <i class="fab fa-instagram text-pink-500 text-xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Instagram</h3>
                    <a href="https://instagram.com/riaspestapku" target="_blank"
                       class="text-pink-600 hover:text-pink-700 font-medium text-sm">
                        @riaspestapku
                    </a>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-clock text-blue-500 text-xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Jam Operasional</h3>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><span class="font-medium">Setiap hari:</span> 07.00 – 21.00 WIB</p>
                    </div>
                </div>
            </div>

            {{-- Form Kontak --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h2 class="font-playfair text-2xl font-bold text-gray-800 mb-6">Kirim Pesan</h2>

                @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-5 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
                @endif

                <form class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"
                                   placeholder="Nama Anda">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp</label>
                            <input type="text"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"
                                   placeholder="08xx-xxxx-xxxx">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rencana Tanggal Pernikahan</label>
                        <input type="date"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Paket yang Diminati</label>
                        <select class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                            <option value="">-- Pilih Paket --</option>
                            <option>Silver - Rp 8.000.000</option>
                            <option>Gold - Rp 14.000.000</option>
                            <option>Platinum I - Rp 16.500.000</option>
                            <option>Platinum II - Rp 18.000.000</option>
                            <option>Diamond I - Rp 20.000.000</option>
                            <option>Diamond II - Rp 22.000.000</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pesan</label>
                        <textarea rows="4"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"
                                  placeholder="Ceritakan kebutuhan pernikahan Anda..."></textarea>
                    </div>

                    {{-- Redirect ke WhatsApp --}}
                    <button type="button" onclick="sendWhatsApp()"
                            class="w-full bg-green-500 text-white py-3 rounded-xl font-bold hover:bg-green-600 transition text-sm">
                        <i class="fab fa-whatsapp mr-2"></i>Kirim via WhatsApp
                    </button>
                    <p class="text-center text-xs text-gray-400">
                        Pesan akan dikirimkan langsung ke WhatsApp kami
                    </p>
                </form>
            </div>
        </div>

        {{-- Map --}}
        <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <iframe
                src="https://www.google.com/maps?q=Jl.+Delima+Gg+Delima+VII+No.3+Pekanbaru&output=embed"
                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
            <div class="p-4 text-center border-t border-gray-100">
                <a href="https://maps.app.goo.gl/a2iJ7iebtDoVkkkK9" target="_blank"
                   class="text-rose-600 hover:text-rose-700 font-medium text-sm">
                    <i class="fas fa-location-arrow mr-1"></i>Buka di Google Maps
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
function sendWhatsApp() {
    const nama    = document.querySelector('input[placeholder="Nama Anda"]').value;
    const wa      = document.querySelector('input[placeholder="08xx-xxxx-xxxx"]').value;
    const tanggal = document.querySelector('input[type="date"]').value;
    const paket   = document.querySelector('select').value;
    const pesan   = document.querySelector('textarea').value;

    if (!nama || !wa) {
        alert('Mohon isi nama dan nomor WhatsApp terlebih dahulu.');
        return;
    }

    const msg = `Halo Rias Pesta Pekanbaru 👋\n\nSaya ingin konsultasi mengenai paket pernikahan.\n\n*Nama:* ${nama}\n*No. WA:* ${wa}\n*Tgl Pernikahan:* ${tanggal || '-'}\n*Paket:* ${paket || '-'}\n\n*Pesan:*\n${pesan || '-'}`;

    window.open('https://wa.me/6281276031567?text=' + encodeURIComponent(msg), '_blank');
}
</script>
@endpush