@extends('layouts.app')

@section('title', 'Laba Rugi')
@section('header', 'Laporan Laba Rugi')
@section('subheader', 'Income Statement - ' . $periodLabel)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Period Filter --}}
    <div class="card">
        <form action="{{ route('reports.income-statement') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <select name="period" class="form-select w-36" onchange="toggleMonth()">
                    <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>
            <div id="monthSelect" class="{{ $period === 'yearly' ? 'hidden' : '' }}">
                <select name="month" class="form-select w-36">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}</option>
                    @endfor
                </select>
            </div>
            <select name="year" class="form-select w-28">
                @for($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <label class="flex items-center gap-2 text-sm text-neutral-400">
                <input type="checkbox" name="compare" value="1" class="w-4 h-4 rounded" {{ request('compare') ? 'checked' : '' }}>
                Bandingkan
            </label>
            <button type="submit" class="btn btn-primary">Filter</button>
            <div class="flex items-center gap-2 ml-auto">
                <a href="{{ route('reports.income-statement.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="btn btn-secondary text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    CSV
                </a>
                <a href="{{ route('reports.income-statement.export', request()->query()) }}" target="_blank" class="btn btn-secondary text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print
                </a>
            </div>
        </form>
    </div>

    {{-- Revenues --}}
    <div class="card">
        <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
            <div class="w-6 h-6 rounded bg-success-500/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
            PENDAPATAN
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-dark-border">
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2">Kode</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2">Nama Akun</th>
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-2 px-2">{{ $periodLabel }}</th>
                        @if($comparison)
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-2 px-2">{{ $comparison['label'] }}</th>
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-2 px-2">Selisih</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border">
                    @php $totalRevenue = 0; $prevTotalRevenue = 0; @endphp
                    @foreach($revenues as $account)
                        @php
                            $totalRevenue += $account->balance;
                            $prevBalance = $comparison ? ($comparison['data'][$account->id] ?? 0) : 0;
                            $prevTotalRevenue += $prevBalance;
                            $diff = $account->balance - $prevBalance;
                        @endphp
                        <tr>
                            <td class="py-2 px-2 text-sm font-mono text-neutral-400">{{ $account->code }}</td>
                            <td class="py-2 px-2 text-sm text-white">{{ $account->name }}</td>
                            <td class="py-2 px-2 text-sm text-right text-white">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
                            @if($comparison)
                            <td class="py-2 px-2 text-sm text-right text-neutral-400">Rp {{ number_format($prevBalance, 0, ',', '.') }}</td>
                            <td class="py-2 px-2 text-sm text-right {{ $diff >= 0 ? 'text-success-400' : 'text-danger-400' }}">
                                {{ $diff >= 0 ? '+' : '' }}Rp {{ number_format($diff, 0, ',', '.') }}
                            </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-success-500/10">
                        <td colspan="2" class="py-3 px-2 text-sm font-semibold text-right text-success-400">Total Pendapatan</td>
                        <td class="py-3 px-2 text-sm font-bold text-right text-success-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                        @if($comparison)
                        <td class="py-3 px-2 text-sm font-bold text-right text-neutral-400">Rp {{ number_format($prevTotalRevenue, 0, ',', '.') }}</td>
                        <td class="py-3 px-2 text-sm font-bold text-right {{ ($totalRevenue - $prevTotalRevenue) >= 0 ? 'text-success-400' : 'text-danger-400' }}">
                            {{ ($totalRevenue - $prevTotalRevenue) >= 0 ? '+' : '' }}Rp {{ number_format($totalRevenue - $prevTotalRevenue, 0, ',', '.') }}
                        </td>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Expenses --}}
    <div class="card">
        <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
            <div class="w-6 h-6 rounded bg-warning-500/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            BEBAN
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-dark-border">
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2">Kode</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2">Nama Akun</th>
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-2 px-2">{{ $periodLabel }}</th>
                        @if($comparison)
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-2 px-2">{{ $comparison['label'] }}</th>
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-2 px-2">Selisih</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border">
                    @php $totalExpense = 0; $prevTotalExpense = 0; @endphp
                    @foreach($expenses as $account)
                        @php
                            $totalExpense += $account->balance;
                            $prevBalance = $comparison ? ($comparison['data'][$account->id] ?? 0) : 0;
                            $prevTotalExpense += $prevBalance;
                            $diff = $account->balance - $prevBalance;
                        @endphp
                        <tr>
                            <td class="py-2 px-2 text-sm font-mono text-neutral-400">{{ $account->code }}</td>
                            <td class="py-2 px-2 text-sm text-white">{{ $account->name }}</td>
                            <td class="py-2 px-2 text-sm text-right text-white">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
                            @if($comparison)
                            <td class="py-2 px-2 text-sm text-right text-neutral-400">Rp {{ number_format($prevBalance, 0, ',', '.') }}</td>
                            <td class="py-2 px-2 text-sm text-right {{ $diff <= 0 ? 'text-success-400' : 'text-danger-400' }}">
                                {{ $diff >= 0 ? '+' : '' }}Rp {{ number_format($diff, 0, ',', '.') }}
                            </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-warning-500/10">
                        <td colspan="2" class="py-3 px-2 text-sm font-semibold text-right text-warning-400">Total Beban</td>
                        <td class="py-3 px-2 text-sm font-bold text-right text-warning-400">Rp {{ number_format($totalExpense, 0, ',', '.') }}</td>
                        @if($comparison)
                        <td class="py-3 px-2 text-sm font-bold text-right text-neutral-400">Rp {{ number_format($prevTotalExpense, 0, ',', '.') }}</td>
                        <td class="py-3 px-2 text-sm font-bold text-right {{ ($totalExpense - $prevTotalExpense) <= 0 ? 'text-success-400' : 'text-danger-400' }}">
                            {{ ($totalExpense - $prevTotalExpense) >= 0 ? '+' : '' }}Rp {{ number_format($totalExpense - $prevTotalExpense, 0, ',', '.') }}
                        </td>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Net Income --}}
    @php
        $netIncome = $totalRevenue - $totalExpense;
        $prevNetIncome = $prevTotalRevenue - $prevTotalExpense;
        $netDiff = $netIncome - $prevNetIncome;
    @endphp
    <div class="card {{ $netIncome >= 0 ? 'bg-gradient-to-r from-success-500/10 to-primary-500/10 border-success-500/20' : 'bg-gradient-to-r from-danger-500/10 to-warning-500/10 border-danger-500/20' }}">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl {{ $netIncome >= 0 ? 'bg-success-500/20' : 'bg-danger-500/20' }} flex items-center justify-center">
                    <svg class="w-7 h-7 {{ $netIncome >= 0 ? 'text-success-400' : 'text-danger-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($netIncome >= 0)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        @endif
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-neutral-400">{{ $netIncome >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}</p>
                    <p class="text-2xl font-bold {{ $netIncome >= 0 ? 'text-success-400' : 'text-danger-400' }}">
                        Rp {{ number_format(abs($netIncome), 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <div class="text-center sm:text-right">
                <div class="grid grid-cols-{{ $comparison ? '3' : '2' }} gap-4 text-sm">
                    <div>
                        <p class="text-neutral-500">Pendapatan</p>
                        <p class="font-medium text-success-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-neutral-500">Beban</p>
                        <p class="font-medium text-warning-400">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
                    </div>
                    @if($comparison)
                    <div>
                        <p class="text-neutral-500">vs {{ $comparison['label'] }}</p>
                        <p class="font-medium {{ $netDiff >= 0 ? 'text-success-400' : 'text-danger-400' }}">
                            {{ $netDiff >= 0 ? '+' : '' }}Rp {{ number_format($netDiff, 0, ',', '.') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali
    </a>

</div>

@push('scripts')
<script>
function toggleMonth() {
    const period = document.querySelector('select[name="period"]').value;
    const monthSelect = document.getElementById('monthSelect');
    monthSelect.classList.toggle('hidden', period === 'yearly');
}
</script>
@endpush
@endsection
