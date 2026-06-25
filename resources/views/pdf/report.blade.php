<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Booking</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1f2937; }
        .header { background: #e11d48; color: white; padding: 20px 30px; }
        .header h1 { font-size: 20px; font-weight: bold; }
        .header p { font-size: 10px; opacity: 0.85; margin-top: 2px; }
        .period { padding: 12px 30px; background: #fff1f2; border-bottom: 2px solid #fecdd3; display: flex; justify-content: space-between; }
        .period p { font-size: 11px; color: #6b7280; }
        .period strong { color: #1f2937; }
        .summary { padding: 16px 30px; display: flex; gap: 16px; background: #f9fafb; border-bottom: 1px solid #e5e7eb; }
        .summary-card { flex: 1; background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; text-align: center; }
        .summary-card .num { font-size: 22px; font-weight: bold; color: #e11d48; }
        .summary-card .label { font-size: 9px; text-transform: uppercase; color: #9ca3af; margin-top: 2px; }
        .body { padding: 20px 30px; }
        .section-title { font-size: 11px; font-weight: bold; text-transform: uppercase; color: #e11d48; letter-spacing: 1px; border-bottom: 1px solid #fecdd3; padding-bottom: 6px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        table th { background: #f9fafb; text-align: left; padding: 7px 8px; font-size: 9px; text-transform: uppercase; color: #6b7280; border-bottom: 1px solid #e5e7eb; }
        table td { padding: 7px 8px; border-bottom: 1px solid #f3f4f6; font-size: 10px; }
        table tr:nth-child(even) td { background: #fafafa; }
        .badge { display: inline-block; padding: 1px 6px; border-radius: 999px; font-size: 9px; font-weight: bold; }
        .badge-green  { background: #dcfce7; color: #166534; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-yellow { background: #fef9c3; color: #854d0e; }
        .badge-purple { background: #f3e8ff; color: #6b21a8; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .footer { margin-top: 20px; padding: 12px 30px; background: #f9fafb; border-top: 1px solid #e5e7eb; text-align: center; font-size: 9px; color: #9ca3af; }
    </style>
</head>
<body>

<div class="header">
    <h1>LAPORAN BOOKING</h1>
    <p>Rias Pesta Pekanbaru — Wedding Organizer</p>
    <p>Jl. Delima Gg Delima VII No. 3, Pekanbaru | 0812-7603-567</p>
</div>

<div class="period">
    <p>Periode: <strong>{{ $startDate->format('d F Y') }} — {{ $endDate->format('d F Y') }}</strong></p>
    <p>Dicetak: <strong>{{ now()->format('d F Y, H:i') }} WIB</strong></p>
</div>

<div class="summary">
    <div class="summary-card">
        <div class="num">{{ $summary['total_bookings'] }}</div>
        <div class="label">Total Booking</div>
    </div>
    <div class="summary-card">
        <div class="num">{{ $bookings->where('status','confirmed')->count() }}</div>
        <div class="label">Dikonfirmasi</div>
    </div>
    <div class="summary-card">
        <div class="num">{{ $bookings->where('status','completed')->count() }}</div>
        <div class="label">Selesai</div>
    </div>
    <div class="summary-card">
        <div class="num" style="font-size:14px;">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
        <div class="label">Total Pendapatan</div>
    </div>
</div>

<div class="body">
    <p class="section-title">Detail Booking</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Pengantin</th>
                <th>Customer</th>
                <th>Tgl Acara</th>
                <th>Total</th>
                <th>Dibayar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $i => $booking)
            @php
                $badgeClass = match($booking->status) {
                    'confirmed'   => 'badge-blue',
                    'in_progress' => 'badge-purple',
                    'completed'   => 'badge-green',
                    'cancelled'   => 'badge-red',
                    default       => 'badge-yellow',
                };
                $statusLabel = [
                    'pending'     => 'Pending',
                    'confirmed'   => 'Konfirmasi',
                    'in_progress' => 'Diproses',
                    'completed'   => 'Selesai',
                    'cancelled'   => 'Batal',
                ];
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $booking->booking_code }}</strong></td>
                <td>{{ $booking->groom_name }} & {{ $booking->bride_name }}</td>
                <td>{{ $booking->user->name }}</td>
                <td>{{ $booking->event_date->format('d M Y') }}</td>
                <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($booking->totalPaid(), 0, ',', '.') }}</td>
                <td><span class="badge {{ $badgeClass }}">{{ $statusLabel[$booking->status] ?? $booking->status }}</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; padding: 20px; color: #9ca3af;">Tidak ada data pada periode ini</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="footer">
    <p>Laporan ini digenerate secara otomatis oleh Sistem Informasi Rias Pesta Pekanbaru</p>
</div>

</body>
</html>