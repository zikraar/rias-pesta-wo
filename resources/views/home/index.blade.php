@extends('layouts.app')
@section('title', 'Beranda')
@section('content')

{{-- Hero Section --}}
<section class="relative bg-gradient-to-br from-rose-900 via-rose-700 to-pink-600 text-white overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-96 h-96 rounded-full border-8 border-white transform translate-x-1/3 -translate-y-1/3"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 rounded-full border-8 border-white transform -translate-x-1/3 translate-y-1/3"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 py-24 flex flex-col lg:flex-row items-center gap-12">
        <div class="lg:w-1/2 text-center lg:text-left">
            <span class="inline-block bg-rose-500 bg-opacity-40 text-rose-100 text-xs font-semibold px-3 py-1 rounded-full mb-4 uppercase tracking-wider">
                ✨ Wedding Organizer Pekanbaru
            </span>
            <h1 class="font-playfair text-5xl lg:text-6xl font-bold leading-tight mb-6">
                Wujudkan <br><span class="text-yellow-300">Pernikahan</span><br>Impian Anda
            </h1>
            <p class="text-rose-100 text-lg mb-8 max-w-xl">
                Rias Pesta Pekanbaru hadir untuk mewujudkan setiap detail pernikahan Anda dengan sentuhan profesional, penuh cinta, dan tak terlupakan.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                <a href="{{ route('register') }}"
                   class="bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-bold px-8 py-4 rounded-xl transition text-center">
                    Pesan Sekarang
                </a>
                <a href="{{ route('packages') }}"
                   class="border-2 border-white text-white hover:bg-white hover:text-rose-700 font-semibold px-8 py-4 rounded-xl transition text-center">
                    Lihat Paket
                </a>
            </div>
        </div>
        <div class="lg:w-1/2">
            <div class="bg-white bg-opacity-10 backdrop-blur rounded-2xl p-8 grid grid-cols-2 gap-4">
                <div class="bg-white bg-opacity-20 rounded-xl p-5 text-center">
                    <p class="font-playfair text-4xl font-bold text-yellow-300">500+</p>
                    <p class="text-rose-100 text-sm mt-1">Pasangan Bahagia</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-xl p-5 text-center">
                    <p class="font-playfair text-4xl font-bold text-yellow-300">10+</p>
                    <p class="text-rose-100 text-sm mt-1">Tahun Pengalaman</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-xl p-5 text-center">
                    <p class="font-playfair text-4xl font-bold text-yellow-300">3</p>
                    <p class="text-rose-100 text-sm mt-1">Paket Tersedia</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-xl p-5 text-center">
                    <p class="font-playfair text-4xl font-bold text-yellow-300">100%</p>
                    <p class="text-rose-100 text-sm mt-1">Kepuasan Klien</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Paket Unggulan --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-rose-500 text-sm font-semibold uppercase tracking-widest">Pilihan Terbaik</span>
            <h2 class="font-playfair text-4xl font-bold text-gray-800 mt-2">Paket Layanan Kami</h2>
            <p class="text-gray-500 mt-3 max-w-xl mx-auto">Pilih paket yang sesuai dengan kebutuhan dan anggaran pernikahan Anda</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($packages as $index => $pkg)
            <div class="relative rounded-2xl border-2 {{ $index === 1 ? 'border-rose-500 shadow-xl shadow-rose-100' : 'border-gray-100 shadow-md' }} overflow-hidden transition hover:-translate-y-1 duration-300">
                @if($index === 1)
                    <div class="absolute top-0 right-0 bg-rose-500 text-white text-xs font-bold px-4 py-1.5 rounded-bl-xl">
                        TERPOPULER ⭐
                    </div>
                @endif
                <div class="{{ $index === 1 ? 'bg-gradient-to-br from-rose-500 to-pink-600' : 'bg-gray-50' }} p-6">
                    <p class="{{ $index === 1 ? 'text-rose-100' : 'text-gray-500' }} text-sm uppercase tracking-wider font-semibold">{{ $pkg->category }}</p>
                    <h3 class="{{ $index === 1 ? 'text-white' : 'text-gray-800' }} font-playfair text-2xl font-bold mt-1">{{ $pkg->name }}</h3>
                    <div class="mt-3">
                        <span class="{{ $index === 1 ? 'text-yellow-300' : 'text-rose-600' }} text-3xl font-bold">
                            Rp {{ number_format($pkg->price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <ul class="space-y-2 mb-6">
                        @foreach($pkg->includes as $item)
                            <li class="flex items-start text-sm text-gray-600">
                                <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('register') }}"
                       class="block text-center {{ $index === 1 ? 'bg-rose-600 hover:bg-rose-700 text-white' : 'border-2 border-rose-600 text-rose-600 hover:bg-rose-600 hover:text-white' }} font-semibold py-3 rounded-xl transition">
                        Pilih Paket Ini
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('packages') }}" class="text-rose-600 hover:text-rose-700 font-medium text-sm">
                Lihat semua paket <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
</section>

{{-- Portfolio Teaser --}}
@if($portfolios->count() > 0)
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-rose-500 text-sm font-semibold uppercase tracking-widest">Karya Kami</span>
            <h2 class="font-playfair text-4xl font-bold text-gray-800 mt-2">Portfolio Pernikahan</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($portfolios as $item)
                <div class="relative rounded-xl overflow-hidden aspect-square group">
                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition duration-300 flex items-end p-4">
                        <p class="text-white font-semibold text-sm opacity-0 group-hover:opacity-100 transition">{{ $item->title }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('portfolio') }}"
               class="inline-block bg-rose-600 hover:bg-rose-700 text-white px-8 py-3 rounded-xl font-semibold transition">
                Lihat Semua Portfolio
            </a>
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="py-20 bg-gradient-to-r from-rose-600 to-pink-600 text-white text-center">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="font-playfair text-4xl font-bold mb-4">Siap Memulai?</h2>
        <p class="text-rose-100 text-lg mb-8">Konsultasikan pernikahan impian Anda bersama kami sekarang.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}"
               class="bg-white text-rose-600 hover:bg-gray-100 font-bold px-8 py-4 rounded-xl transition">
                Daftar & Pesan Sekarang
            </a>
            <a href="https://wa.me/6281234567890"
               class="border-2 border-white text-white hover:bg-white hover:text-rose-600 font-semibold px-8 py-4 rounded-xl transition" target="_blank">
                <i class="fab fa-whatsapp mr-2"></i>Hubungi via WhatsApp
            </a>
        </div>
    </div>
</section>

@endsection