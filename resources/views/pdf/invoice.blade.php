<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; }
        .header { background: #e11d48; color: white; padding: 20px 30px; }
        .header h1 { font-size: 22px; }
        .header p { font-size: 11px; opacity: 0.8; margin-top: 2px; }
        .invoice-meta { background: #fff1f2; padding: 15px 30px; border-bottom: 2px solid #fecdd3; }
        .invoice-meta table { width: 100%; }
        .body { padding: 20px 30px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #e11d48; color: white; padding: 8px 12px; text-align: left; font-size: 11px; }
        td { padding: 8px 12px; border-bottom: 1px solid #f3f4f6; font-size: 11px; }
        tr:nth-child(even) td { background: #fef2f2; }
        .total-row td { font-weight: bold; background: #fff1f2; font-size: 13px; border-top: 2px solid #e11d48; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 10px; font-weight: bold; }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-yellow { background: #fef9c3; color: #713f12; }
        .footer { margin-top: 40px; padding-top: 15px; border-top: 1px solid #e5e7eb; text-align: center; font-size: 10px; color: #9ca3af; }
    </style>
</head>
<body>
<div class="header">
    <table>
        <tr>
            <td>
                <h1>♥ Rias Pesta Pekanbaru</h1>
                <p>Jl. Delima Gg Delima VII No. 3, Pekanbaru | +62 812-3456-7890</p>
            </td>
            <td style="text-align:right;">
                <p style="font-size:18px; font-weight:bold;">INVOICE</p>
                <p>{{ $booking->booking_code }}</p>
            </td>
        </tr>
    </table>
</div>

<div class="invoice-meta">
    <table>
        <tr>
            <td><strong>Kepada:</strong> {{ $booking->user->name }}</td>
            <td><strong>Tanggal Invoice:</strong> {{ now()->format('d F Y') }}</td>
        </tr>
        <tr>
            <td>{{ $booking->user->email }}</td>
            <td><strong>Tanggal Acara:</strong> {{ $booking->event_date->format('d F Y') }}</td>
        </tr>
        <tr>
            <td>{{ $booking->user->phone }}</td>
            <td>
                <strong>Status:</strong>
                <span class="badge badge-{{ in_array($booking->status, ['confirmed','in_progress','completed']) ? 'green' : 'yellow' }}">
                    {{ strtoupper($booking->status) }}
                </span>
            </td>
        </tr>
    </table>
</div>

<div class="body">
    <h3 style="margin-bottom: 10px; color: #e11d48;">Detail Acara</h3>
    <table style="margin-bottom: 20px; background: #f9fafb;">
        <tr><td><strong>Pasangan:</strong> {{ $booking->groom_name }} & {{ $booking->bride_name }}</td>
            <td><strong>Lokasi:</strong> {{ $booking->event_location }}</td></tr>
        <tr><td><strong>Jenis Acara:</strong> {{ strtoupper(str_replace('_',' ',$booking->event_type)) }}</td>
            <td><strong>Jumlah Tamu:</strong> {{ number_format($booking->guest_count) }} orang</td></tr>
    </table>

    <h3 style="margin-bottom: 10px; color: #e11d48;">Paket yang Dipesan</h3>
    <table>
        <tr>
            <th>Nama Paket</th>
            <th>Kategori</th>
            <th style="text-align:right;">Harga</th>
        </tr>
        @foreach($booking->packages as $bp)
        <tr>
            <td>{{ $bp->package->name }}</td>
            <td>{{ ucfirst($bp->package->category) }}</td>
            <td style="text-align:right;">Rp {{ number_format($bp->price_snapshot, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="2">TOTAL</td>
            <td style="text-align:right;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
        </tr>
    </table>

    @if($booking->payments->where('status','verified')->count() > 0)
    <h3 style="margin: 20px 0 10px; color: #e11d48;">Riwayat Pembayaran</h3>
    <table>
        <tr>
            <th>Kode</th><th>Jenis</th><th>Tanggal</th><th style="text-align:right;">Jumlah</th><th>Status</th>
        </tr>
        @foreach($booking->payments->where('status','verified') as $pay)
        <tr>
            <td>{{ $pay->payment_code }}</td>
            <td>{{ strtoupper($pay->payment_type) }}</td>
            <td>{{ $pay->transfer_date->format('d M Y') }}</td>
            <td style="text-align:right;">Rp {{ number_format($pay->amount, 0, ',', '.') }}</td>
            <td><span class="badge badge-green">VERIFIED</span></td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="3">TOTAL DIBAYAR</td>
            <td style="text-align:right;">Rp {{ number_format($booking->totalPaid(), 0, ',', '.') }}</td>
            <td></td>
        </tr>
        @if($booking->remainingPayment() > 0)
        <tr style="background:#fef9c3;">
            <td colspan="3" style="font-weight:bold; color:#92400e;">SISA TAGIHAN</td>
            <td style="text-align:right; font-weight:bold; color:#92400e;">Rp {{ number_format($booking->remainingPayment(), 0, ',', '.') }}</td>
            <td></td>
        </tr>
        @endif
    </table>
    @endif
</div>

<div class="footer">
    <p>Terima kasih telah mempercayakan pernikahan Anda kepada Rias Pesta Pekanbaru</p>
    <p style="margin-top:4px;">Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
</div>
</body>
</html>