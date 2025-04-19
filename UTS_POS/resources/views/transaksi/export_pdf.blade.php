<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Transaksi {{ $transaksi->kode_transaksi }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 5px;
        }

        .header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: black;
            padding: 5px;
            border-radius: 5px 5px 0 0;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .company-info {
            text-align: center;
        }

        .company-name {
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .company-tagline {
            font-size: 7px;
            margin-bottom: 5px;
            opacity: 0.9;
        }

        .company-details {
            font-size: 6px;
            line-height: 1.5;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 15px;
        }

        .logo {
            max-height: 40px;
        }
        .transaction-info {
            margin-bottom: 10px;
            font-size: 9px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9px;
        }
        .items-table th {
            border-bottom: 1px solid #000;
            text-align: left;
            padding: 3px 0;
        }
        .items-table td {
            padding: 2px 0;
        }
        .total-section {
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 5px;
            font-size: 10px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 8px;
        }
        .thank-you {
            margin-top: 10px;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
        }
        .payment-method {
            margin-top: 15px;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <img src="{{ asset('autologo-kop.png') }}" class="logo">
        </div>
        <div class="company-info">
            <div class="company-name">PT. AUTOMOBILKU ABADI</div>
            <div class="company-tagline">PREMIUM AUTOMOTIVE SOLUTIONS</div>
            <div class="company-details">
                Jl. Soekarno-Hatta No. 123, Malang 65141<br>
                Phone (1234) 567890 | Fax (1234) 567890<br>
                www.automobilku.co.id
            </div>
        </div>
    </div>

    <div class="transaction-info">
        <table>
            <tr>
                <td width="35%">Kode Transaksi</td>
                <td width="65%">: {{ $transaksi->kode_transaksi }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: {{ $transaksi->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td>: {{ $transaksi->user->nama }}</td>
            </tr>
            <tr>
                <td>Pembeli</td>
                <td>: {{ $transaksi->pembeli }}</td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="25%">Nama</th>
                <th width="30%">Harga</th>
                <th width="15%">Qty</th>
                <th width="30%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->detail as $detail)
            <tr>
                <td width="25%">{{ $detail->barang->nama_barang }}</td>
                <td width="35%">Rp {{ number_format($detail->barang->harga, 0, ',', '.') }}</td>
                <td width="5%">{{ $detail->jumlah }}</td>
                <td width="35%">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>Rp {{ number_format($transaksi->detail->sum('subtotal'), 0, ',', '.') }}</span>
        </div>
        <div class="total-row">
            <span>Diskon ({{ $transaksi->diskon }}%):</span>
            <span>Rp {{ number_format($transaksi->detail->sum('subtotal') * $transaksi->diskon / 100, 0, ',', '.') }}</span>
        </div>
        <div class="total-row" style="font-weight: bold;">
            <span>Total:</span>
            <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="payment-method">
        Metode Pembayaran: {{ $transaksi->metode_pembayaran }}
    </div>

    <div class="thank-you">
        Terima kasih atas pembeliannya!
    </div>

    <div class="footer">
        Struk ini dicetak pada: {{ $timestamp }}<br>
        &copy; {{ date('Y') }} PT. Automobilku Abadi
    </div>
</body>
</html>