<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Rias Pesta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link.active { background: #fef2f2; color: #e11d48; border-right: 3px solid #e11d48; }
        .sidebar-link:hover  { background: #fef2f2; color: #e11d48; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 flex">

    {{-- Sidebar --}}
    <aside class="w-64 min-h-screen bg-white shadow-md flex flex-col fixed top-0 left-0 z-40">
        {{-- Logo --}}
        <div class="p-5 border-b">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-rose-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-heart text-white text-xs"></i>
                </div>
                <div>
                    <p class="font-bold text-gray-800 text-sm">Rias Pesta</p>
                    <p class="text-xs text-gray-400">Admin Panel</p>
                </div>
            </div>
        </div>

        {{-- User Info --}}
        <div class="p-4 border-b bg-rose-50">
            <p class="font-semibold text-gray-700 text-sm">{{ auth()->user()->name }}</p>
            <span class="text-xs bg-rose-100 text-rose-700 px-2 py-0.5 rounded-full">
                {{ ucfirst(auth()->user()->role) }}
            </span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm text-gray-600 transition {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt w-5 mr-3"></i> Dashboard
            </a>
            <a href="{{ route('admin.bookings.index') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm text-gray-600 transition {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check w-5 mr-3"></i> Kelola Booking
                @php $pendingCount = \App\Models\Booking::where('status','pending')->count(); @endphp
                @if($pendingCount > 0)
                    <span class="ml-auto bg-rose-500 text-white text-xs px-1.5 rounded-full">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.payments.index') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm text-gray-600 transition {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
                <i class="fas fa-credit-card w-5 mr-3"></i> Pembayaran
                @php $pendingPay = \App\Models\Payment::where('status','pending')->count(); @endphp
                @if($pendingPay > 0)
                    <span class="ml-auto bg-yellow-500 text-white text-xs px-1.5 rounded-full">{{ $pendingPay }}</span>
                @endif
            </a>
            <a href="{{ route('admin.progress.index') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm text-gray-600 transition {{ request()->routeIs('admin.progress*') ? 'active' : '' }}">
                <i class="fas fa-tasks w-5 mr-3"></i> Progress
            </a>
            <a href="{{ route('admin.events.index') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm text-gray-600 transition {{ request()->routeIs('admin.events*') ? 'active' : '' }}">
                <i class="fas fa-calendar w-5 mr-3"></i> Kalender
            </a>
            <a href="{{ route('admin.packages.index') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm text-gray-600 transition {{ request()->routeIs('admin.packages*') ? 'active' : '' }}">
                <i class="fas fa-box-open w-5 mr-3"></i> Paket Layanan
            </a>
            <a href="{{ route('admin.portfolios.index') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm text-gray-600 transition {{ request()->routeIs('admin.portfolios*') ? 'active' : '' }}">
                <i class="fas fa-images w-5 mr-3"></i> Portfolio
            </a>
            <a href="{{ route('admin.reports.index') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm text-gray-600 transition {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar w-5 mr-3"></i> Laporan
            </a>
            @if(auth()->user()->isSuperAdmin())
            <a href="{{ route('admin.users.index') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm text-gray-600 transition {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="fas fa-users w-5 mr-3"></i> Manajemen User
            </a>
            @endif
        </nav>

        {{-- Logout --}}
        <div class="p-4 border-t">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center px-3 py-2 text-sm text-red-500 hover:bg-red-50 rounded-lg transition">
                    <i class="fas fa-sign-out-alt mr-3"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 ml-64">
        {{-- Top Bar --}}
        <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center sticky top-0 z-30">
            <div>
                <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                <p class="text-xs text-gray-400">@yield('page-subtitle', '')</p>
            </div>
            <div class="flex items-center space-x-3 text-sm text-gray-500">
                <i class="fas fa-calendar-alt"></i>
                <span>{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </header>

        {{-- Flash --}}
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between mb-4">
                    <span><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()">&times;</button>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center justify-between mb-4">
                    <span><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()">&times;</button>
                </div>
            @endif
        </div>

        {{-- Page Content --}}
        <main class="p-6">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>