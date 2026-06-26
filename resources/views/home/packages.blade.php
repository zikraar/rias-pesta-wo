@extends('layouts.app')
@section('title', 'Paket Layanan')

@section('content')

{{-- Header --}}
<section class="bg-gradient-to-br from-rose-900 via-rose-700 to-pink-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <span class="inline-block bg-rose-500 bg-opacity-40 text-rose-100 text-xs font-semibold px-3 py-1 rounded-full mb-4 uppercase tracking-wider">
            House Wedding Packages
        </span>
        <h1 class="font-playfair text-4xl lg:text-5xl font-bold mb-4">Paket Layanan Rias Pesta</h1>
        <p class="text-rose-100 text-lg max-w-2xl mx-auto">
            Pilih paket pernikahan yang sesuai dengan kebutuhan dan anggaran Anda.
        </p>
    </div>
</section>

{{-- Paket Cards --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($packages as $index => $pkg)
            <div class="relative rounded-2xl border-2 {{ $index === 1 ? 'border-rose-500 shadow-xl shadow-rose-100' : 'border-gray-100 shadow-md' }} overflow-hidden transition hover:-translate-y-1 duration-300 flex flex-col">

                {{-- Badge Terpopuler --}}
                @if($index === 1)
                <div class="bg-rose-500 text-white text-xs font-bold px-4 py-2 text-center uppercase tracking-widest">
                    TERPOPULER ⭐
                </div>
                @endif

                {{-- Header Card --}}
                <div class="p-6 {{ $index === 1 ? 'bg-gradient-to-br from-rose-500 to-pink-600 text-white' : 'bg-white' }}">
                    <p class="text-xs font-semibold uppercase tracking-widest {{ $index === 1 ? 'text-rose-100' : 'text-gray-400' }}">
                        {{ ucfirst($pkg->category) }}
                    </p>
                    <h3 class="font-playfair text-3xl font-bold mt-1 {{ $index === 1 ? 'text-white' : 'text-gray-800' }}">
                        {{ $pkg->name }}
                    </h3>
                    <p class="text-2xl font-bold mt-2 {{ $index === 1 ? 'text-yellow-300' : 'text-rose-600' }}">
                        Rp {{ number_format($pkg->price, 0, ',', '.') }}
                    </p>
                </div>

                {{-- Isi Deskripsi --}}
                <div class="p-6 flex-1 border-t {{ $index === 1 ? 'border-rose-100' : 'border-gray-100' }}">
                    @if($pkg->description)
                    <div class="space-y-4 text-sm text-gray-600">
                        @foreach(explode("\n\n", trim($pkg->description)) as $section)
                            @php
                                $lines = explode("\n", trim($section));
                                $heading = array_shift($lines);
                            @endphp
                            <div>
                                <p class="font-bold text-gray-700 text-xs uppercase tracking-wider mb-2">
                                    {{ $heading }}
                                </p>
                                <ul class="space-y-1">
                                    @foreach($lines as $line)
                                        @if(trim($line))
                                        <li class="flex items-start gap-2">
                                            <i class="fas fa-check-circle text-rose-400 text-xs mt-0.5 flex-shrink-0"></i>
                                            <span>{{ ltrim(trim($line), '- ') }}</span>
                                        </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Tombol --}}
                <div class="p-6 border-t border-gray-100">
                    @auth
                        @if(auth()->user()->role === 'customer')
                            <a href="{{ route('customer.bookings.create', ['package' => $pkg->id]) }}"
                               class="w-full block text-center py-3 rounded-xl font-bold text-sm transition
                               {{ $index === 1
                                    ? 'bg-rose-600 text-white hover:bg-rose-700'
                                    : 'border-2 border-rose-500 text-rose-600 hover:bg-rose-50' }}">
                                <i class="fas fa-calendar-check mr-2"></i>Pilih Paket Ini
                            </a>
                        @else
                            <a href="{{ route('admin.packages.index') }}"
                               class="w-full block text-center py-3 rounded-xl font-bold text-sm bg-gray-100 text-gray-600 hover:bg-gray-200 transition">
                                <i class="fas fa-tachometer-alt mr-2"></i>Lihat di Admin
                            </a>
                        @endif
                    @else
                        <a href="{{ route('register') }}"
                           class="w-full block text-center py-3 rounded-xl font-bold text-sm transition
                           {{ $index === 1
                                ? 'bg-rose-600 text-white hover:bg-rose-700'
                                : 'border-2 border-rose-500 text-rose-600 hover:bg-rose-50' }}">
                            <i class="fas fa-calendar-check mr-2"></i>Pilih Paket Ini
                        </a>
                        <p class="text-center text-xs text-gray-400 mt-2">
                            Sudah punya akun? <a href="{{ route('login') }}" class="text-rose-500 hover:underline">Masuk</a>
                        </p>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 bg-gradient-to-r from-rose-600 to-pink-600 text-white text-center">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="font-playfair text-3xl font-bold mb-4">Bingung Memilih Paket?</h2>
        <p class="text-rose-100 mb-8">Konsultasikan kebutuhan pernikahan Anda langsung bersama tim kami.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="https://wa.me/6281276031567" target="_blank"
               class="bg-white text-rose-600 hover:bg-gray-100 font-bold px-8 py-3 rounded-xl transition">
                <i class="fab fa-whatsapp mr-2"></i>Chat WhatsApp
            </a>
            <a href="{{ route('contact') }}"
               class="border-2 border-white text-white hover:bg-white hover:text-rose-600 font-semibold px-8 py-3 rounded-xl transition">
                <i class="fas fa-envelope mr-2"></i>Halaman Kontak
            </a>
        </div>
    </div>
</section>

@endsection