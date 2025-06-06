<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Kasir</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            max-width: 800px;
            margin: auto;
        }

        .logo {
            text-align: right;
        }

        .logo img {
            width: 150px;
        }

        h1 {
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        tfoot th {
            text-align: right;
        }

        .notes {
            margin-top: 20px;
            font-size: 14px;
        }

        address {
            margin-top: 20px;
            font-size: 14px;
            font-style: normal;
        }
    </style>
</head>

<body>
    <h1>Invoice - #2</h1>
    <p>Member Name : {{ $member->name }}</p>
    <p>No. HP : {{ $member->phone_number }}</p>
    <p>Bergabung Sejak : {{ $member->created_at }}</p>
    <p>Point Member : {{ $member->poin_member }}</p>
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Sub total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($details as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>Rp.{{ $item->product->price }}</td>
                <td>{{ $item->qty }}</td>
                <td>Rp.{{ $transaction->total_price }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Poin Member</th>
                <td>{{ $member->poin_member }}</td>
            </tr>
            <tr>
                <th colspan="3">Tunai</th>
                <td>Rp.{{ $transaction->total_price }}</td>
            </tr>
            <tr>
                <th colspan="3">Kembalian</th>
                <td>Rp.{{ $transaction->total_price }}</td>
            </tr>
            <tr>
                <th colspan="3">Total</th>
                <td>Rp.{{ $transaction->total_price }}</td>
            </tr>
        </tfoot>
    </table>
    <div class="notes">
        Terima kasih atas pembelian Anda.
    </div>
    <hr>
    <address>
        GacorStore<br>
        Alamat: Cigombong Anjay Mabar<br>
        Email: gacorStore@gacor.com
    </address>
</body>

</html>
