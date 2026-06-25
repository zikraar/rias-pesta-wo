<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $booking->booking_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #1f2937; }
        .header { background: #e11d48; color: white; padding: 24px 32px; }
        .header h1 { font-size: 22px; font-weight: bold; }
        .header p { font-size: 11px; opacity: 0.85; margin-top: 2px; }
        .invoice-info { display: flex; justify-content: space-between; padding: 20px 32px; background: #fff1f2; border-bottom: 2px solid #fecdd3; }
        .invoice-info .label { font-size: 10px; color: #9ca3af; text-transform: uppercase; }
        .invoice-info .value { font-size: 13px; font-weight: bold; color: #1f2937; margin-top: 2px; }
        .body { padding: 24px 32px; }
        .section-title { font-size: 11px; font-weight: bold; text-transform: uppercase; color: #e11d48; letter-spacing: 1px; border-bottom: 1px solid #fecdd3; padding-bottom: 6px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th { background: #f9fafb; text-align: left; padding: 8px 10px; font-size: 10px; text-transform: uppercase; color: #6b7280; border-bottom: 1px solid #e5e7eb; }
        table td { padding: 8px 10px; border-bottom: 1px solid #f3f4f6; font-size: 11px; }
        .total-row td { font-weight: bold; background: #fff1f2; color: #e11d48; font-size: 13px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 999px; font-size: 10px; font-weight: bold; }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-yellow { background: #fef9c3; color: #854d0e; }
        .footer { margin-top: 32px; padding: 16px 32px; background: #f9fafb; border-top: 1px solid #e5e7eb; text-align: center; font-size: 10px; color: #9ca3af; }
    </style>
</head>
<body>

<div class="header">
    <h1>INVOICE</h1>
    <p>Rias Pesta Pekanbaru — Wedding Organizer</p>
    <p>Jl. Delima Gg Delima VII No. 3, Pekanbaru | 0812-7603-567</p>
</div>

<div class="invoice-info">
    <div>
        <p class="label">No. Invoice</p>
        <p class="value">{{ $booking->booking_code }}</p>
    </div>
    <div>
        <p class="label">Tanggal Cetak</p>
        <p class="value">{{ now()->format('d F Y') }}</p>
    </div>
    <div>
        <p class="label">Tanggal Acara</p>
        <p class="value">{{ $booking->event_date->format('d F Y') }}</p>
    </div>
    <div>
        <p class="label">Status</p>
        <p class="value">
            <span class="badge {{ in_array($booking->status, ['confirmed','in_progress','completed']) ? 'badge-green' : 'badge-yellow' }}">
                {{ strtoupper($booking->status) }}
            </span>
        </p>
    </div>
</div>

<div class="body">
    <p class="section-title">Data Pelanggan & Acara</p>
    <table style="margin-bottom: 20px;">
        <tr>
            <td style="width:50%"><strong>Nama Customer:</strong> {{ $booking->user->name }}</td>
            <td><strong>Tanggal Acara:</strong> {{ $booking->event_date->format('d F Y') }}</td>
        </tr>
        <tr>
            <td>{{ $booking->user->phone }}</td>
            <td><strong>Jenis Acara:</strong> {{ ucfirst(str_replace('_',' ',$booking->event_type)) }}</td>
        </tr>
        <tr>
            <td><strong>Pengantin:</strong> {{ $booking->groom_name }} & {{ $booking->bride_name }}</td>
            <td><strong>Lokasi:</strong> {{ $booking->event_location }}</td>
        </tr>
    </table>

    <p class="section-title">Paket yang Dipesan</p>
    <table>
        <tr>
            <th>Nama Paket</th>
            <th>Kategori</th>
            <th style="text-align:right">Harga</th>
        </tr>
        @foreach($booking->packages as $bp)
        <tr>
            <td>{{ $bp->package->name }}</td>
            <td>{{ ucfirst($bp->package->category) }}</td>
            <td style="text-align:right">Rp {{ number_format($bp->price_snapshot, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="2">TOTAL TAGIHAN</td>
            <td style="text-align:right">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
        </tr>
    </table>

    @if($booking->payments->where('status','verified')->count() > 0)
    <p class="section-title">Riwayat Pembayaran</p>
    <table>
        <tr>
            <th>Kode</th><th>Jenis</th><th>Tanggal</th><th style="text-align:right">Jumlah</th>
        </tr>
        @foreach($booking->payments->where('status','verified') as $pay)
        <tr>
            <td>{{ $pay->payment_code }}</td>
            <td>{{ ucfirst($pay->payment_type) }}</td>
            <td>{{ $pay->transfer_date->format('d M Y') }}</td>
            <td style="text-align:right">Rp {{ number_format($pay->amount, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="3">TOTAL DIBAYAR</td>
            <td style="text-align:right">Rp {{ number_format($booking->totalPaid(), 0, ',', '.') }}</td>
        </tr>
        @if($booking->remainingPayment() > 0)
        <tr style="background:#fef9c3;">
            <td colspan="3" style="font-weight:bold;color:#92400e;">SISA TAGIHAN</td>
            <td style="text-align:right;font-weight:bold;color:#92400e;">Rp {{ number_format($booking->remainingPayment(), 0, ',', '.') }}</td>
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