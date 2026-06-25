<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Rias Pesta Pekanbaru') - Wedding Organizer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-playfair { font-family: 'Playfair Display', serif; }
        .rose-gradient { background: linear-gradient(135deg, #ffe4e6 0%, #fdf2f8 50%, #fff1f2 100%); }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50">

    {{-- Navbar --}}
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-rose-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-heart text-white text-xs"></i>
                    </div>
                    <span class="font-playfair text-xl font-bold text-gray-800">Rias Pesta</span>
                </a>

                {{-- Nav Links --}}
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}"      class="text-gray-600 hover:text-rose-600 transition font-medium">Beranda</a>
                    <a href="{{ route('packages') }}"  class="text-gray-600 hover:text-rose-600 transition font-medium">Paket</a>
                    <a href="{{ route('portfolio') }}" class="text-gray-600 hover:text-rose-600 transition font-medium">Portfolio</a>
                    <a href="{{ route('contact') }}"   class="text-gray-600 hover:text-rose-600 transition font-medium">Kontak</a>
                </div>

                {{-- Auth Buttons --}}
                <div class="flex items-center space-x-3">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                               class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition">
                                <i class="fas fa-tachometer-alt mr-1"></i> Admin
                            </a>
                        @else
                            {{-- Notifikasi --}}
                            <div class="relative">
                                <button onclick="toggleNotif()" class="relative text-gray-600 hover:text-rose-600 p-2">
                                    <i class="fas fa-bell text-lg"></i>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <span class="absolute top-0 right-0 w-4 h-4 bg-rose-500 text-white text-xs rounded-full flex items-center justify-center">
                                            {{ auth()->user()->unreadNotifications->count() }}
                                        </span>
                                    @endif
                                </button>
                                <div id="notifDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden">
                                    <div class="p-3 border-b bg-rose-50">
                                        <p class="font-semibold text-gray-700 text-sm">Notifikasi</p>
                                    </div>
                                    <div class="max-h-64 overflow-y-auto">
                                        @forelse(auth()->user()->notifications->take(5) as $notif)
                                            <a href="{{ $notif->data['url'] ?? '#' }}"
                                               class="block px-4 py-3 hover:bg-gray-50 border-b {{ $notif->read_at ? 'opacity-60' : '' }}">
                                                <p class="text-xs text-gray-700">{{ $notif->data['message'] }}</p>
                                                <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                            </a>
                                        @empty
                                            <p class="p-4 text-sm text-gray-400 text-center">Tidak ada notifikasi</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('customer.dashboard') }}"
                               class="bg-rose-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-rose-700 transition">
                                <i class="fas fa-user mr-1"></i> Dashboard
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-red-500 text-sm px-3 py-2">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-gray-600 hover:text-rose-600 font-medium text-sm">Masuk</a>
                        <a href="{{ route('register') }}"
                           class="bg-rose-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-rose-700 transition">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between">
                <span><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">&times;</button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center justify-between">
                <span><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">&times;</button>
            </div>
        </div>
    @endif

    @yield('content')

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-300 mt-16">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-rose-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-heart text-white text-xs"></i>
                        </div>
                        <span class="font-playfair text-xl font-bold text-white">Rias Pesta</span>
                    </div>
                    <p class="text-sm text-gray-400">Wedding Organizer profesional di Pekanbaru yang telah mewujudkan ratusan pernikahan impian.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Kontak</h4>
                    <div class="space-y-2 text-sm">
                        <p><i class="fas fa-map-marker-alt mr-2 text-rose-400"></i>Jl. Delima Gg Delima VII No. 3, Pekanbaru</p>
                        <p><i class="fas fa-phone mr-2 text-rose-400"></i>+62 812-3456-7890</p>
                        <p><i class="fas fa-envelope mr-2 text-rose-400"></i>info@riaspesta.com</p>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Jam Operasional</h4>
                    <div class="space-y-1 text-sm text-gray-400">
                        <p>Senin – Sabtu: 08.00 – 20.00 WIB</p>
                        <p>Minggu: 09.00 – 17.00 WIB</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Rias Pesta Pekanbaru. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        function toggleNotif() {
            const el = document.getElementById('notifDropdown');
            el.classList.toggle('hidden');
        }
        document.addEventListener('click', (e) => {
            const el = document.getElementById('notifDropdown');
            if (el && !el.contains(e.target) && !e.target.closest('button[onclick="toggleNotif()"]')) {
                el.classList.add('hidden');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>