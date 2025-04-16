<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Struk Transaksi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            padding: 10px;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .line {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        td, th {
            padding: 4px 0;
            font-size: 12px;
        }

        .summary {
            margin-top: 15px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 6px;
            background-color: #f9f9f9;
        }

        .summary-row {
            display: flex;
            justify-content: flex-end; /* Align to the right */
            margin-bottom: 6px;
            font-size: 12px;
        }

        .summary-row.total {
            font-weight: bold;
            border-top: 1px dashed #aaa;
            padding-top: 6px;
            margin-top: 8px;
        }

        /* Style for the logo */
        .logo {
            text-align: center;
            margin-bottom: 10px;
        }

        /* Align the summary items to the right */
        .summary-row span {
            display: inline-block;
            text-align: right;
            width: 50%;
        }

        /* Custom style for total amounts */
        .summary-row span.amount {
            text-align: right;
        }
    </style>
</head>
<body>
    <!-- Logo at the top -->
    <div class="logo">
        <img src="{{ public_path('assets/img/logo.jpg') }}" alt="Logo" style="max-height: 100px;">
    </div>

    <h2 class="text-center">Bukti Penjualan</h2>
    <p><strong>Indo April</strong></p>

    <p>
        Member Status : {{ $transaction->member ? 'Member' : 'Non-member' }}<br>
        No. HP : {{ $transaction->member->no_hp ?? '-' }}<br>
        Bergabung Sejak : {{ $transaction->member ? \Carbon\Carbon::parse($transaction->member->created_at)->format('d F Y') : '-' }}<br>
        Poin Member : {{ $transaction->member->poin ?? '-' }}
    </p>

    <div class="line"></div>

    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Qty</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->penjualan->detailPenjualan as $item)
                <tr>
                    <td>{{ $item->produk->nama_produk }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <div class="summary">
        <table>
            <tr>
                <td>Total Harga:</td>
                <td class="text-right">Rp {{ number_format($transaction->sub_total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Poin Digunakan:</td>
                <td class="text-right">{{ $transaction->poin_digunakan ?? 0 }}</td>
            </tr>
            <tr>
                <td>Harga Setelah Poin:</td>
                <td class="text-right">Rp {{ number_format(($transaction->sub_total - ($transaction->poin_digunakan ?? 0)), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Jumlah yang Dibayarkan:</td>
                <td class="text-right">Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}</td>
            </tr>
            <tr style="border-top: 1px dashed #aaa;" class="bold">
                <td>Total Kembalian:</td>
                <td class="text-right">Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
    

    <p class="mt-3">{{ $transaction->created_at->format('Y-m-d H:i:s') }} | {{ $transaction->user->name }}</p>
    <p class="text-center">Terima kasih atas pembelian Anda!</p>
    <p class="text-center">Jl. Mawar No. 123, Jakarta Selatan<br>Telp: (021) 12345678</p>
</body>
</html>
