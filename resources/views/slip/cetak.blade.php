<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - {{ $payroll->user->name }} {{ $payroll->getNamaBulanAttribute() }} {{ $payroll->tahun }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #f4a261;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h3 {
            margin: 0;
            color: #f4a261;
        }
        .company {
            font-size: 14px;
            font-weight: bold;
        }
        .subtitle {
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .info-table td, .info-table th {
            padding: 6px 0;
            border: none;
        }
        .detail-table td, .detail-table th {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .detail-table th {
            background-color: #f8f9fa;
        }
        .text-end {
            text-align: right;
        }
        .fw-bold {
            font-weight: bold;
        }
        .total {
            border-top: 2px solid #000;
            font-size: 14px;
            margin-top: 10px;
            padding-top: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="company">PT. KIN MEDIA CREATAMA</div>
        <div>Jl. Wonosari Km 10, Yogyakarta</div>
        <div class="subtitle">Slip Gaji Karyawan</div>
    </div>

    <div class="row">
        <table class="info-table">
            <tr><td style="width: 120px;">Nama</td><td>: {{ $payroll->user->name }}</td></tr>
            <tr><td>Jabatan</td><td>: {{ $payroll->jabatan ?? 'Staff' }}</td></tr>
            <tr><td>Periode</td><td>: {{ $payroll->getNamaBulanAttribute() }} {{ $payroll->tahun }}</td></tr>
            <tr><td>Tanggal Cetak</td><td>: {{ now()->format('d/m/Y H:i') }}</td></tr>
        </table>
    </div>

    <table class="detail-table">
        <thead>
            <tr><th colspan="2">Komponen Gaji</th></tr>
            <tr><th>Deskripsi</th><th class="text-end">Jumlah</th></tr>
        </thead>
        <tbody>
            <tr><td>Gaji Pokok</td><td class="text-end">Rp {{ number_format($payroll->gaji_pokok, 0, ',', '.') }}</td></tr>
            <tr><td>Uang Makan</td><td class="text-end">Rp {{ number_format($payroll->uang_makan, 0, ',', '.') }}</td></tr>
            <tr><td>Tunjangan</td><td class="text-end">Rp {{ number_format($payroll->tunjangan, 0, ',', '.') }}</td></tr>
            <tr><td>Lembur</td><td class="text-end">Rp {{ number_format($payroll->lembur, 0, ',', '.') }}</td></tr>
            <tr style="border-top:1px solid #ddd;"><td><strong>Total Pendapatan</strong></td><td class="text-end"><strong>Rp {{ number_format($payroll->gaji_pokok + $payroll->uang_makan + $payroll->tunjangan + $payroll->lembur, 0, ',', '.') }}</strong></td></tr>
            <tr><td>Potongan</td><td class="text-end">Rp {{ number_format($payroll->potongan, 0, ',', '.') }}</td></tr>
        </tbody>
    </table>

    <div class="total text-end">
        <strong>Gaji Bersih : Rp {{ number_format($payroll->total_gaji, 0, ',', '.') }}</strong>
    </div>

    @if($payroll->keterangan)
    <div style="margin-top:15px; padding:8px; background:#f9f9f9; border-left:3px solid #f4a261;">
        <small>Keterangan: {{ $payroll->keterangan }}</small>
    </div>
    @endif

    <div class="footer">
        Terima kasih atas kerja keras Anda.<br>
        Dicetak secara elektronik, tidak memerlukan tanda tangan.
    </div>
</div>
<div class="no-print" style="text-align:center; margin-top:20px;">
    <button onclick="window.print();">Cetak / Simpan PDF</button>
    <button onclick="window.close();">Tutup</button>
</div>
</body>
</html>