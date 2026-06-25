@extends('layouts.app')
@section('title', 'Portfolio')

@section('content')

{{-- Header --}}
<section class="bg-gradient-to-br from-rose-900 via-rose-700 to-pink-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <span class="inline-block bg-rose-500 bg-opacity-40 text-rose-100 text-xs font-semibold px-3 py-1 rounded-full mb-4 uppercase tracking-wider">
            Karya Kami
        </span>
        <h1 class="font-playfair text-4xl lg:text-5xl font-bold mb-4">Portfolio Pernikahan</h1>
        <p class="text-rose-100 text-lg max-w-2xl mx-auto">
            Setiap momen pernikahan adalah karya yang kami jaga dengan sepenuh hati.
        </p>
    </div>
</section>

{{-- Filter Kategori --}}
@if($categories->count() > 0)
<section class="bg-white border-b border-gray-100 sticky top-16 z-40">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center gap-3 overflow-x-auto">
        <a href="{{ route('portfolio') }}"
           class="whitespace-nowrap px-4 py-1.5 rounded-full text-sm font-medium transition
           {{ !request('category') ? 'bg-rose-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Semua
        </a>
        @foreach($categories as $cat)
        <a href="{{ route('portfolio', ['category' => $cat]) }}"
           class="whitespace-nowrap px-4 py-1.5 rounded-full text-sm font-medium transition capitalize
           {{ request('category') == $cat ? 'bg-rose-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            {{ ucfirst($cat) }}
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- Grid Portfolio --}}
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        @if($portfolios->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($portfolios as $item)
            <div class="relative rounded-xl overflow-hidden aspect-square group cursor-pointer"
                 onclick="openModal('{{ asset('storage/'.$item->image) }}', '{{ addslashes($item->title) }}', '{{ ucfirst($item->category) }}')">
                <img src="{{ asset('storage/'.$item->image) }}"
                     alt="{{ $item->title }}"
                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition duration-300 flex items-end p-4">
                    <div class="opacity-0 group-hover:opacity-100 transition duration-300">
                        <p class="text-white font-semibold text-sm">{{ $item->title }}</p>
                        <p class="text-rose-200 text-xs capitalize">{{ $item->category }}</p>
                    </div>
                </div>
                @if($item->is_featured)
                <div class="absolute top-2 right-2">
                    <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-0.5 rounded-full">
                        <i class="fas fa-star mr-1"></i>Unggulan
                    </span>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($portfolios->hasPages())
        <div class="mt-10">{{ $portfolios->links() }}</div>
        @endif

        @else
        <div class="text-center py-20 text-gray-400">
            <i class="fas fa-images text-6xl mb-4 block"></i>
            <p class="font-medium text-lg">Belum ada foto portfolio</p>
            <p class="text-sm mt-1">Foto akan ditampilkan setelah admin menambahkan galeri</p>
        </div>
        @endif
    </div>
</section>

{{-- CTA --}}
<section class="py-16 bg-white">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h2 class="font-playfair text-3xl font-bold text-gray-800 mb-4">Tertarik dengan Karya Kami?</h2>
        <p class="text-gray-500 mb-8">Hubungi kami sekarang dan wujudkan pernikahan impian Anda.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @auth
                @if(auth()->user()->role === 'customer')
                <a href="{{ route('customer.bookings.create') }}"
                   class="bg-rose-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-rose-700 transition">
                    <i class="fas fa-calendar-check mr-2"></i>Pesan Sekarang
                </a>
                @endif
            @else
            <a href="{{ route('register') }}"
               class="bg-rose-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-rose-700 transition">
                <i class="fas fa-user-plus mr-2"></i>Daftar & Pesan
            </a>
            @endauth
            <a href="https://wa.me/628127603567" target="_blank"
               class="bg-green-500 text-white px-8 py-3 rounded-xl font-semibold hover:bg-green-600 transition">
                <i class="fab fa-whatsapp mr-2"></i>Chat WhatsApp
            </a>
        </div>
    </div>
</section>

{{-- Modal Preview Foto --}}
<div id="photoModal" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4"
     onclick="closeModal()">
    <div class="relative max-w-4xl w-full" onclick="event.stopPropagation()">
        <button onclick="closeModal()"
                class="absolute -top-10 right-0 text-white text-2xl hover:text-rose-300 transition">
            <i class="fas fa-times"></i>
        </button>
        <img id="modalImg" src="" alt="" class="w-full max-h-screen object-contain rounded-xl">
        <div class="mt-3 text-center">
            <p id="modalTitle" class="text-white font-semibold text-lg"></p>
            <p id="modalCat" class="text-rose-300 text-sm capitalize"></p>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openModal(src, title, cat) {
    document.getElementById('modalImg').src = src;
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalCat').textContent = cat;
    document.getElementById('photoModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeModal() {
    document.getElementById('photoModal').classList.add('hidden');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
@endpush