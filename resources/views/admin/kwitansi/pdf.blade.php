<!DOCTYPE html>
<html>
<head>
    <style>
        @page { 
            margin: 0; 
            size: a4 landscape; 
        }
        body { 
            font-family: 'Courier', sans-serif;
            margin: 0; 
            padding: 30px; 
            color: #000;
        }

        /* Container Utama */
        .master-table {
            width: 100%;
            border: 2px solid #000;
            border-collapse: collapse;
            table-layout: fixed; /* Kunci agar width td dipatuhi */
        }

        /* Pembagian Kolom yang Pasti */
        .col-stub {
            width: 25%; /* Arsip dibuat sempit */
            border-right: 2px dashed #000;
            padding: 15px 10px;
            vertical-align: top;
            font-size: 11px;
        }

        .col-main {
            width: 75%; /* Bagian Utama dibuat lebar */
            padding: 20px 30px;
            vertical-align: top;
        }

        /* Header Kop */
        .header-box { width: 100%; border-bottom: 1px solid #000; padding-bottom: 10px; margin-bottom: 15px; }
        .nama-instansi { font-size: 20px; font-weight: bold; margin: 0; }
        .alamat-instansi { font-size: 11px; margin: 2px 0; }
        
        /* Judul Kwitansi Sejajar Kanan */
        .judul-wrapper {
            float: right;
            text-align: right;
        }
        .judul-kwitansi {
            font-size: 24px;
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
        }

        /* Baris Data */
        .data-table { width: 100%; margin-top: 10px; border-collapse: collapse; }
        .data-table td { padding: 10px 0; font-size: 15px; }
        .label { width: 180px; }
        .line { border-bottom: 1px dotted #000; }

        /* Box Nominal */
        .box-amount {
            border: 4px double #000;
            padding: 12px 25px;
            font-size: 20px;
            font-weight: bold;
            background: #f2f2f2;
            display: inline-block;
            margin-top: 20px;
        }

        /* Tanda Tangan */
        .footer-table { width: 100%; margin-top: 20px; }
        .sig-box { text-align: center; width: 40%; vertical-align: top; }
        .clear { clear: both; }
    </style>
</head>
<body>

@php
    function terbilang($n) {
        $satuan = ['', 'satu','dua','tiga','empat','lima','enam','tujuh','delapan','sembilan','sepuluh','sebelas'];
        if ($n < 12) return $satuan[$n];
        if ($n < 20) return terbilang($n - 10) . ' belas';
        if ($n < 100) return terbilang((int)($n/10)) . ' puluh' . (($n%10!=0) ? ' ' . terbilang($n%10) : '');
        if ($n < 200) return 'seratus' . (($n-100)!=0 ? ' ' . terbilang($n-100) : '');
        if ($n < 1000) return terbilang((int)($n/100)) . ' ratus' . (($n%100!=0) ? ' ' . terbilang($n%100) : '');
        if ($n < 1000000) return terbilang((int)($n/1000)) . ' ribu' . (($n%1000!=0) ? ' ' . terbilang($n%1000) : '');
        return (string)$n;
    }
    
    $nama = optional($pembayaran->peserta->user)->name ?? '-';
    $jumlah = $pembayaran->nominal ?? 0;
    $untuk = $pembayaran->peserta->program ?? ($pembayaran->peserta->asal_sekolah ?? '-');
    $tgl = date('d F Y', strtotime($pembayaran->tanggal));
    
    $instansi = "CV. WOKA PROJECT MANDIRI";
    $alamat = "Jl. Jambu Kel. Tejosari Kec. Metro Timur Kota Metro 34125";
    $telp = "Telp: 0857-8398-6998";
@endphp

<table class="master-table">
    <tr>
        <td class="col-stub">
            <div style="text-align: center; border-bottom: 1px solid #000; margin-bottom: 10px; font-size: 14px;"><strong>ARSIP</strong></div>
            <table style="width: 100%; font-size: 11px;">
                <tr><td>No:</td></tr>
                <tr><td style="border-bottom: 1px dotted #000; padding-bottom: 5px;">{{ $nomor }}</td></tr>
                <tr><td style="padding-top: 10px;">Sudah Terima Dari:</td></tr>
                <tr><td style="border-bottom: 1px dotted #000; padding-bottom: 5px;">{{ $nama }}</td></tr>
                <tr><td style="padding-top: 10px;">Untuk Pembayaran:</td></tr>
                <tr><td style="border-bottom: 1px dotted #000; padding-bottom: 5px;">{{ $untuk }}</td></tr>
                <tr><td style="padding-top: 10px;">Jumlah:</td></tr>
                <tr><td style="font-weight: bold;">Rp {{ number_format($jumlah,0,',','.') }}</td></tr>
                <tr><td style="padding-top: 10px;">Tanggal:</td></tr>
                <tr><td>{{ $tgl }}</td></tr>
            </table>
            <br><br>
            <div style="text-align: center;">(................)</div>
        </td>

        <td class="col-main">
            <div class="header-box">
                <div class="judul-wrapper">
                    <p class="judul-kwitansi">KWITANSI</p>
                </div>
                <p class="nama-instansi">{{ $instansi }}</p>
                <p class="alamat-instansi">{{ $alamat }}</p>
                <p class="alamat-instansi">{{ $telp }}</p>
                <div class="clear"></div>
            </div>

            <table class="data-table">
                <tr>
                    <td class="label">No. Kwitansi</td>
                    <td style="width: 15px;">:</td>
                    <td class="line" style="color: #d00; font-weight: bold;">{{ $nomor }}</td>
                </tr>
                <tr>
                    <td class="label">Sudah Terima Dari</td>
                    <td>:</td>
                    <td class="line" style="text-transform: uppercase;">{{ $nama }}</td>
                </tr>
                <tr>
                    <td class="label">Banyaknya Uang</td>
                    <td>:</td>
                    <td class="line" style="background: #f9f9f9; font-style: italic;">
                        === {{ ucfirst(terbilang($jumlah)) }} rupiah ===
                    </td>
                </tr>
                <tr>
                    <td class="label">Untuk Pembayaran</td>
                    <td>:</td>
                    <td class="line">{{ $untuk }}</td>
                </tr>
            </table>

            <table class="footer-table">
                <tr>
                    <td style="vertical-align: bottom;">
                        <div class="box-amount">
                            Rp. {{ number_format($jumlah,0,',','.') }},-
                        </div>
                    </td>
                    <td class="sig-box">
                        <div style="margin-bottom: 60px;">Metro, {{ $tgl }}</div>
                        <table style="width: 100%;">
                            <tr>
                                <td style="text-align: center;">
                                    Penyetor,<br><br><br>
                                    ( <strong>{{ $nama }}</strong> )
                                </td>
                                <td style="text-align: center;">
                                    Penerima,<br><br><br>
                                    ( <strong>Rahman Ardi S.</strong> )
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            
            <div style="margin-top: 15px; font-size: 10px; font-style: italic; border-top: 1px solid #eee; padding-top: 5px;">
                * Kwitansi ini adalah bukti pembayaran yang sah.
            </div>
        </td>
    </tr>
</table>

</body>
</html>