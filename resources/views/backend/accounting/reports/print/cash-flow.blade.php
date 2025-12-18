<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Arus Kas - {{ $periodLabel }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; padding: 20px; }
        h1 { font-size: 18px; text-align: center; margin-bottom: 5px; }
        .subtitle { font-size: 12px; text-align: center; color: #666; margin-bottom: 20px; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 12px; font-weight: bold; background: #f5f5f5; padding: 8px 10px; border: 1px solid #ddd; }
        .row { display: flex; justify-content: space-between; padding: 8px 10px; border: 1px solid #ddd; border-top: none; }
        .row.indent { padding-left: 30px; }
        .row.total { background: #f5f5f5; font-weight: bold; }
        .text-right { text-align: right; }
        .net-cash { margin-top: 20px; border: 2px solid #333; padding: 15px; text-align: center; }
        .net-cash h2 { font-size: 14px; margin-bottom: 5px; }
        .net-cash .amount { font-size: 20px; font-weight: bold; }
        @media print { body { padding: 0; } .no-print { display: none; } }
        .btn-print { position: fixed; top: 20px; right: 20px; background: #0066cc; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; }
    </style>
</head>
<body>
    <button class="btn-print no-print" onclick="window.print()">🖨️ Cetak</button>

    <h1>LAPORAN ARUS KAS (Cash Flow Statement)</h1>
    <p class="subtitle">Periode: {{ $periodLabel }}</p>

    <div class="section">
        <div class="section-title">AKTIVITAS OPERASI</div>
        <div class="row indent">
            <span>Penerimaan dari Operasi</span>
            <span>Rp {{ number_format($cashFlowData['operating']['inflow'], 0, ',', '.') }}</span>
        </div>
        <div class="row indent">
            <span>Pengeluaran untuk Operasi</span>
            <span>(Rp {{ number_format($cashFlowData['operating']['outflow'], 0, ',', '.') }})</span>
        </div>
        <div class="row total">
            <span>Arus Kas Bersih dari Aktivitas Operasi</span>
            <span>Rp {{ number_format($cashFlowData['operating']['net'], 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">AKTIVITAS INVESTASI</div>
        <div class="row total">
            <span>Arus Kas Bersih dari Aktivitas Investasi</span>
            <span>Rp {{ number_format($cashFlowData['investing']['net'], 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">AKTIVITAS PENDANAAN</div>
        <div class="row total">
            <span>Arus Kas Bersih dari Aktivitas Pendanaan</span>
            <span>Rp {{ number_format($cashFlowData['financing']['net'], 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="net-cash">
        <h2>TOTAL PERUBAHAN KAS</h2>
        <p class="amount">Rp {{ number_format($cashFlowData['total'], 0, ',', '.') }}</p>
    </div>
</body>
</html>
