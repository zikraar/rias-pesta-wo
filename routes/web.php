<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController, AuthController,
    Admin\DashboardController as AdminDashboard,
    Admin\PackageController as AdminPackage,
    Admin\BookingController as AdminBooking,
    Admin\PaymentController as AdminPayment,
    Admin\ProgressController as AdminProgress,
    Admin\EventController as AdminEvent,
    Admin\PortfolioController as AdminPortfolio,
    Admin\ReportController,
    Admin\UserController,
    Customer\DashboardController as CustomerDashboard,
    Customer\BookingController as CustomerBooking,
    Customer\PaymentController as CustomerPayment,
    Customer\ProgressController as CustomerProgress,
};

// ===== PUBLIC ROUTES =====
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/paket', [HomeController::class, 'packages'])->name('packages');
Route::get('/portfolio', [HomeController::class, 'portfolio'])->name('portfolio');
Route::get('/kontak', [HomeController::class, 'contact'])->name('contact');

// ===== AUTH =====
require __DIR__.'/auth.php';

// ===== CUSTOMER ROUTES =====
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');
    Route::resource('bookings', CustomerBooking::class);
    Route::get('bookings/{booking}/progress', [CustomerProgress::class, 'index'])->name('bookings.progress');
    Route::resource('payments', CustomerPayment::class)->only(['index', 'create', 'store', 'show']);
    Route::get('profile', [CustomerDashboard::class, 'profile'])->name('profile');
    Route::put('profile', [CustomerDashboard::class, 'updateProfile'])->name('profile.update');
});

// ===== ADMIN ROUTES =====
Route::middleware(['auth', 'role:admin,superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('packages', AdminPackage::class);
    Route::resource('bookings', AdminBooking::class);
    Route::resource('payments', AdminPayment::class)->only(['index', 'show', 'update']);
    Route::post('payments/{payment}/verify', [AdminPayment::class, 'verify'])->name('payments.verify');
    Route::post('payments/{payment}/reject', [AdminPayment::class, 'reject'])->name('payments.reject');
    Route::resource('progress', AdminProgress::class)->except(['show']);
    Route::resource('events', AdminEvent::class);
    Route::resource('portfolios', AdminPortfolio::class);
    Route::resource('users', UserController::class);
    // Laporan & Export
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
    Route::get('bookings/{booking}/invoice', [AdminBooking::class, 'invoice'])->name('bookings.invoice');
});

// Redirect setelah login berdasarkan role
Route::get('/dashboard', function () {
    return match(auth()->user()->role) {
        'superadmin', 'admin' => redirect()->route('admin.dashboard'),
        'customer'            => redirect()->route('customer.dashboard'),
        default               => redirect()->route('home'),
    };
})->middleware('auth')->name('dashboard');