<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laba Rugi - {{ $periodLabel }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; padding: 20px; }
        h1 { font-size: 18px; text-align: center; margin-bottom: 5px; }
        .subtitle { font-size: 12px; text-align: center; color: #666; margin-bottom: 20px; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 12px; font-weight: bold; background: #f5f5f5; padding: 6px 10px; border: 1px solid #ddd; margin-bottom: 0; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th { background: #f9f9f9; padding: 6px 8px; text-align: left; border: 1px solid #ddd; font-weight: bold; }
        td { padding: 5px 8px; border: 1px solid #ddd; }
        .text-right { text-align: right; }
        .total-row { background: #f5f5f5; font-weight: bold; }
        .net-income { margin-top: 20px; border: 2px solid #333; padding: 15px; text-align: center; }
        .net-income h2 { font-size: 14px; margin-bottom: 5px; }
        .net-income .amount { font-size: 20px; font-weight: bold; }
        @media print { body { padding: 0; } .no-print { display: none; } }
        .btn-print { position: fixed; top: 20px; right: 20px; background: #0066cc; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; }
    </style>
</head>
<body>
    <button class="btn-print no-print" onclick="window.print()">🖨️ Cetak</button>

    <h1>LAPORAN LABA RUGI (Income Statement)</h1>
    <p class="subtitle">Periode: {{ $periodLabel }}</p>

    <div class="section">
        <div class="section-title">PENDAPATAN</div>
        <table>
            <thead>
                <tr>
                    <th style="width:80px">Kode</th>
                    <th>Nama Akun</th>
                    <th style="width:120px" class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php $totalRevenue = 0; @endphp
                @foreach($revenues as $account)
                    @php $totalRevenue += $account->balance; @endphp
                    <tr>
                        <td>{{ $account->code }}</td>
                        <td>{{ $account->name }}</td>
                        <td class="text-right">{{ number_format($account->balance, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2" class="text-right">Total Pendapatan</td>
                    <td class="text-right">{{ number_format($totalRevenue, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="section">
        <div class="section-title">BEBAN</div>
        <table>
            <thead>
                <tr>
                    <th style="width:80px">Kode</th>
                    <th>Nama Akun</th>
                    <th style="width:120px" class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php $totalExpense = 0; @endphp
                @foreach($expenses as $account)
                    @php $totalExpense += $account->balance; @endphp
                    <tr>
                        <td>{{ $account->code }}</td>
                        <td>{{ $account->name }}</td>
                        <td class="text-right">{{ number_format($account->balance, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2" class="text-right">Total Beban</td>
                    <td class="text-right">{{ number_format($totalExpense, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    @php $netIncome = $totalRevenue - $totalExpense; @endphp
    <div class="net-income">
        <h2>{{ $netIncome >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH' }}</h2>
        <p class="amount">Rp {{ number_format(abs($netIncome), 0, ',', '.') }}</p>
    </div>
</body>
</html>
