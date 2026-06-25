<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Rias Pesta Pekanbaru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } .font-playfair { font-family: 'Playfair Display', serif; } </style>
</head>
<body class="min-h-screen flex">
    {{-- Left Panel --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-rose-800 via-rose-600 to-pink-500 items-center justify-center p-12 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-40 h-40 rounded-full border-4 border-white"></div>
            <div class="absolute bottom-20 right-10 w-60 h-60 rounded-full border-4 border-white"></div>
        </div>
        <div class="relative text-center text-white">
            <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white fill-current" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
            </div>
            <h1 class="font-playfair text-4xl font-bold mb-4">Rias Pesta</h1>
            <p class="text-rose-100 text-lg mb-2">Wedding Organizer Pekanbaru</p>
            <p class="text-rose-200 text-sm max-w-xs mx-auto">Mewujudkan hari pernikahan impian Anda dengan sentuhan profesional dan penuh kasih.</p>
            <div class="mt-8 flex justify-center space-x-6 text-center">
                <div>
                    <p class="text-3xl font-bold">500+</p>
                    <p class="text-rose-200 text-xs">Pasangan Bahagia</p>
                </div>
                <div class="border-l border-rose-400"></div>
                <div>
                    <p class="text-3xl font-bold">10+</p>
                    <p class="text-rose-200 text-xs">Tahun Pengalaman</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Panel (Form) --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
        <div class="w-full max-w-md">
            <div class="text-center mb-8 lg:hidden">
                <h1 class="font-playfair text-3xl font-bold text-rose-700">Rias Pesta</h1>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-1">Selamat Datang</h2>
                <p class="text-gray-500 text-sm mb-6">Masuk ke akun Anda untuk melanjutkan</p>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent text-sm @error('email') border-red-400 @enderror"
                            placeholder="nama@email.com">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-400 text-sm pr-12 @error('password') border-red-400 @enderror"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePass()"
                                    class="absolute right-4 top-3.5 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye text-sm" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center text-sm text-gray-600">
                            <input type="checkbox" name="remember" class="mr-2 rounded border-gray-300 text-rose-600">
                            Ingat saya
                        </label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-rose-600 hover:text-rose-700">Lupa password?</a>
                        @endif
                    </div>

                    <button type="submit"
                            class="w-full bg-rose-600 hover:bg-rose-700 text-white font-semibold py-3 rounded-xl transition duration-200 text-sm">
                        Masuk
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500 mt-6">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-rose-600 hover:text-rose-700 font-medium">Daftar sekarang</a>
                </p>

                {{-- Demo Accounts --}}
                <div class="mt-4 p-3 bg-gray-50 rounded-xl text-xs text-gray-400 border border-dashed border-gray-200">
                    <p class="font-medium text-gray-500 mb-1">Akun Demo:</p>
                    <p>Admin: admin@riaspesta.com / password123</p>
                    <p>Customer: customer@demo.com / password123</p>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        function togglePass() {
            const p = document.getElementById('password');
            const i = document.getElementById('eyeIcon');
            if (p.type === 'password') { p.type = 'text'; i.className = 'fas fa-eye-slash text-sm'; }
            else { p.type = 'password'; i.className = 'fas fa-eye text-sm'; }
        }
    </script>
</body>
</html>