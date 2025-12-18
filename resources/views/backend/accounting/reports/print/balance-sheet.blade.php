<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Neraca - {{ $periodLabel }}</title>
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
        .summary { margin-top: 20px; border: 2px solid #333; padding: 10px; }
        .summary-row { display: flex; justify-content: space-between; padding: 4px 0; }
        @media print { body { padding: 0; } .no-print { display: none; } }
        .btn-print { position: fixed; top: 20px; right: 20px; background: #0066cc; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; }
    </style>
</head>
<body>
    <button class="btn-print no-print" onclick="window.print()">🖨️ Cetak</button>

    <h1>NERACA (Balance Sheet)</h1>
    <p class="subtitle">Periode: {{ $periodLabel }}</p>

    <div class="section">
        <div class="section-title">ASET</div>
        <table>
            <thead>
                <tr>
                    <th style="width:80px">Kode</th>
                    <th>Nama Akun</th>
                    <th style="width:120px" class="text-right">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @php $totalAssets = 0; @endphp
                @foreach($assets as $account)
                    @php $totalAssets += $account->balance; @endphp
                    <tr>
                        <td>{{ $account->code }}</td>
                        <td>{{ $account->name }}</td>
                        <td class="text-right">{{ number_format($account->balance, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2" class="text-right">Total Aset</td>
                    <td class="text-right">{{ number_format($totalAssets, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="section">
        <div class="section-title">LIABILITAS</div>
        <table>
            <thead>
                <tr>
                    <th style="width:80px">Kode</th>
                    <th>Nama Akun</th>
                    <th style="width:120px" class="text-right">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @php $totalLiabilities = 0; @endphp
                @foreach($liabilities as $account)
                    @php $totalLiabilities += $account->balance; @endphp
                    <tr>
                        <td>{{ $account->code }}</td>
                        <td>{{ $account->name }}</td>
                        <td class="text-right">{{ number_format($account->balance, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2" class="text-right">Total Liabilitas</td>
                    <td class="text-right">{{ number_format($totalLiabilities, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="section">
        <div class="section-title">EKUITAS</div>
        <table>
            <thead>
                <tr>
                    <th style="width:80px">Kode</th>
                    <th>Nama Akun</th>
                    <th style="width:120px" class="text-right">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @php $totalEquity = 0; @endphp
                @foreach($equity as $account)
                    @php $totalEquity += $account->balance; @endphp
                    <tr>
                        <td>{{ $account->code }}</td>
                        <td>{{ $account->name }}</td>
                        <td class="text-right">{{ number_format($account->balance, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2" class="text-right">Total Ekuitas</td>
                    <td class="text-right">{{ number_format($totalEquity, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="summary">
        <div class="summary-row">
            <span><strong>Total Aset:</strong></span>
            <span><strong>Rp {{ number_format($totalAssets, 0, ',', '.') }}</strong></span>
        </div>
        <div class="summary-row">
            <span><strong>Total Liabilitas + Ekuitas:</strong></span>
            <span><strong>Rp {{ number_format($totalLiabilities + $totalEquity, 0, ',', '.') }}</strong></span>
        </div>
    </div>
</body>
</html>
