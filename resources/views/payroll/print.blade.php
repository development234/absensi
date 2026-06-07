<!DOCTYPE html>
<html>
<head>
    <title>Laporan Payroll {{ $bulanNama }} {{ $tahun }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 12px; }
        .container { width: 100%; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h3 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; text-align: right; }
        @media print {
            .no-print { display: none; }
            button { display: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>Laporan Payroll Karyawan</h3>
            <p>Bulan: {{ $bulanNama }} {{ $tahun }}</p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Karyawan</th>
                    <th>Jabatan</th>
                    <th class="text-right">Gaji Pokok</th>
                    <th class="text-right">Uang Makan</th>
                    <th class="text-right">Tunjangan</th>
                    <th class="text-right">Lembur</th>
                    <th class="text-right">Potongan</th>
                    <th class="text-right">Total Gaji</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payrolls as $index => $p)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $p->user->name }}</td>
                    <td>{{ $p->jabatan ?? '-' }}</td>
                    <td class="text-right">{{ number_format($p->gaji_pokok,0,',','.') }}</td>
                    <td class="text-right">{{ number_format($p->uang_makan,0,',','.') }}</td>
                    <td class="text-right">{{ number_format($p->tunjangan,0,',','.') }}</td>
                    <td class="text-right">{{ number_format($p->lembur,0,',','.') }}</td>
                    <td class="text-right">{{ number_format($p->potongan,0,',','.') }}</td>
                    <td class="text-right"><strong>{{ number_format($p->total_gaji,0,',','.') }}</strong></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8" class="text-right"><strong>Total Keseluruhan</strong></td>
                    <td class="text-right"><strong>{{ number_format($totalSemua,0,',','.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
        
        <div class="footer">
            <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
        <div class="no-print" style="text-align:center; margin-top:20px;">
            <button onclick="window.print();">Cetak / Simpan PDF</button>
            <button onclick="window.close();">Tutup</button>
        </div>
    </div>
</body>
</html>