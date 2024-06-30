<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota Kecil</title>

    <?php
    $style = '
    <style>
        * {
            font-family: "consolas", sans-serif;
        }
        p {
            display: block;
            margin: 3px;
            font-size: 10pt;
        }
        table td {
            font-size: 9pt;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }

        @media print {
            @page {
                margin: 0;
                size: 75mm 
    ';
    ?>
    <?php 
    $style .= 
        ! empty($_COOKIE['innerHeight'])
            ? $_COOKIE['innerHeight'] .'mm; }'
            : '}';
    ?>
    <?php
    $style .= '
            html, body {
                width: 70mm;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
    ';
    ?>

    {!! $style !!}
</head>
<body onload="window.print()">
    <button class="btn-print" style="position: absolute; right: 1rem; top: rem;" onclick="window.print()">Print</button>
    <div class="text-center">
        <h3 style="margin-bottom: 5px;">{{ strtoupper($setting->nama_perusahaan) }}</h3>
        <p>{{ strtoupper($setting->alamat) }}</p>
    </div>
    <br>
    <div>
        
        <p style="float: left">kasir:{{ strtoupper(auth()->user()->name) }}</p>
    </div>
    <div class="clear-both" style="clear: both;"></div>
    <p>No.nota: {{ $penjualan->nomor_nota}}</p>
    <p class="text-center">===================================</p>
    <table width="100%" style="border: 0;">
        @foreach ($detail as $item)
            <tr>
                <td colspan="3">{{ $item->produk->nama_produk }} <td class="text-left">{{ $item->jumlah }}</td><td class="text-right">{{ format_uang($item->jumlah * $item->harga_jual) }}</td></td>
            </tr>
            <tr>
                <td>Disc. {{format_uang($item->produk->diskon * $item->jumlah)}}</td>
            </tr>
        @endforeach
    </table>
    <p class="text-center">-----------------------------------</p>

    <table width="100%" style="border: 0;">
        <tr>
            <td>Total Item</td>
            <td class="text-center">{{ format_uang($penjualan->total_item)}}</td><td class="text-right">{{ format_uang($penjualan->total_harga) }}</td>
        </tr>
        @if (!empty($penjualan->diskon != 0))
            <tr>
                <td>Disc Member</td>
                <td></td>
                <td class="text-right">{{ format_uang($penjualan->diskon) }}</td>
            </tr>
        @endif
        <tr>
            <td>Total Disc</td>
            <td></td>
            <td class="text-right">{{ format_uang($penjualan->total_diskon) }}</td>
        </tr>
        <tr>
            <td>Total Belanja</td>
            <td></td>
            <td class="text-right">{{ format_uang($penjualan->bayar) }}</td>
        </tr>
        <tr>
            <td>Tunai</td>
            <td></td>
            <td class="text-right">{{ format_uang($penjualan->diterima) }}</td>
        </tr>
        <tr>
            <td>Kembalian</td>
            <td></td>
            <td class="text-right">{{ format_uang($penjualan->diterima - $penjualan->bayar) }}</td>
        </tr>
    </table>

    <p class="text-center">===================================</p>
    <p style="float: left;">tgl:{{ $penjualan->created_at }}</p>
    <div class="clear-both" style="clear: both;"></div>
    @if (!empty($penjualan->member->nama))
        <p style="float: left;">member:{{ $penjualan->member->nama }}</p>
    @endif
    <p class="text-center">===================================</p>
    <p class="text-center">-- TERIMA KASIH --</p>

    <script>
        let body = document.body;
        let html = document.documentElement;
        let height = Math.max(
                body.scrollHeight, body.offsetHeight,
                html.clientHeight, html.scrollHeight, html.offsetHeight
            );

        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "innerHeight="+ ((height + 50) * 0.264583);
    </script>
</body>
</html>