<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Besar - {{ now()->format('d/m/Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; padding: 20px; }
        h1 { font-size: 18px; text-align: center; margin-bottom: 5px; }
        .subtitle { font-size: 12px; text-align: center; color: #666; margin-bottom: 20px; }
        .account-section { margin-bottom: 30px; page-break-inside: avoid; }
        .account-header { background: #f5f5f5; padding: 8px 12px; border: 1px solid #ddd; font-weight: bold; display: flex; justify-content: space-between; }
        .account-header .code { color: #333; }
        .account-header .balance { color: #0066cc; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th { background: #f9f9f9; padding: 6px 8px; text-align: left; border: 1px solid #ddd; font-weight: bold; }
        td { padding: 5px 8px; border: 1px solid #ddd; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { background: #f5f5f5; font-weight: bold; }
        .no-data { text-align: center; padding: 20px; color: #999; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
        .btn-print {
            position: fixed; top: 20px; right: 20px;
            background: #0066cc; color: white;
            border: none; padding: 10px 20px;
            cursor: pointer; border-radius: 4px;
        }
        .btn-print:hover { background: #0055aa; }
    </style>
</head>
<body>
    <button class="btn-print no-print" onclick="window.print()">🖨️ Cetak</button>

    <h1>BUKU BESAR</h1>
    <p class="subtitle">General Ledger - {{ now()->translatedFormat('d F Y') }}</p>

    @forelse($accounts as $account)
        @if($account->journalDetails->count() > 0)
        <div class="account-section">
            @php
                $totalDebit = $account->journalDetails->sum('debit');
                $totalCredit = $account->journalDetails->sum('credit');
                $balance = $account->normal_balance === 'debit' ? $totalDebit - $totalCredit : $totalCredit - $totalDebit;
            @endphp
            <div class="account-header">
                <span class="code">{{ $account->code }} - {{ $account->name }} ({{ ucfirst($account->type) }})</span>
                <span class="balance">Saldo: Rp {{ number_format(abs($balance), 0, ',', '.') }} ({{ $balance >= 0 ? 'D' : 'K' }})</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="width:80px">Tanggal</th>
                        <th style="width:100px">No. Jurnal</th>
                        <th>Keterangan</th>
                        <th style="width:100px" class="text-right">Debit</th>
                        <th style="width:100px" class="text-right">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($account->journalDetails as $detail)
                    <tr>
                        <td>{{ $detail->journalEntry->date->format('d/m/Y') }}</td>
                        <td>{{ $detail->journalEntry->number }}</td>
                        <td>{{ $detail->description ?? $detail->journalEntry->description }}</td>
                        <td class="text-right">{{ $detail->debit > 0 ? number_format($detail->debit, 0, ',', '.') : '-' }}</td>
                        <td class="text-right">{{ $detail->credit > 0 ? number_format($detail->credit, 0, ',', '.') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="3" class="text-right">Total</td>
                        <td class="text-right">{{ number_format($totalDebit, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($totalCredit, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif
    @empty
    <p class="no-data">Tidak ada data buku besar</p>
    @endforelse

</body>
</html>
