<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Booking, Payment};
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfMonth();

        $bookings = Booking::with(['user', 'payments'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $summary = [
            'total_bookings'   => $bookings->count(),
            'total_confirmed'  => $bookings->where('status', 'confirmed')->count(),
            'total_completed'  => $bookings->where('status', 'completed')->count(),
            'total_cancelled'  => $bookings->where('status', 'cancelled')->count(),
            'total_revenue'    => Payment::where('status', 'verified')
                ->whereBetween('verified_at', [$startDate, $endDate])
                ->sum('amount'),
        ];

        return view('admin.reports.index', compact(
            'bookings', 'summary', 'startDate', 'endDate'
        ));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfMonth();

        $bookings = Booking::with(['user', 'payments'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $summary = [
            'total_bookings'  => $bookings->count(),
            'total_revenue'   => Payment::where('status', 'verified')
                ->whereBetween('verified_at', [$startDate, $endDate])
                ->sum('amount'),
        ];

        $pdf = Pdf::loadView('pdf.report', compact(
            'bookings', 'summary', 'startDate', 'endDate'
        ))->setPaper('a4', 'landscape');

        return $pdf->download("Laporan-{$startDate->format('Y-m-d')}-sd-{$endDate->format('Y-m-d')}.pdf");
    }
}