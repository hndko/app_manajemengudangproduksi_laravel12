<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    /**
     * Show PPh21 calculator form
     */
    public function pph21()
    {
        return view('calculator.pph21');
    }

    /**
     * Calculate PPh21
     */
    public function calculatePph21(Request $request)
    {
        $request->validate([
            'gross_income' => 'required|numeric|min:0',
            'npwp_status' => 'required|in:yes,no',
            'marital_status' => 'required|in:tk,k/0,k/1,k/2,k/3',
        ]);

        $grossIncome = $request->gross_income;
        $hasNpwp = $request->npwp_status === 'yes';
        $maritalStatus = $request->marital_status;

        // Annual gross income
        $annualGross = $grossIncome * 12;

        // PTKP (Penghasilan Tidak Kena Pajak) 2024
        $ptkp = match($maritalStatus) {
            'tk' => 54000000,      // Tidak Kawin
            'k/0' => 58500000,     // Kawin tanpa tanggungan
            'k/1' => 63000000,     // Kawin dengan 1 tanggungan
            'k/2' => 67500000,     // Kawin dengan 2 tanggungan
            'k/3' => 72000000,     // Kawin dengan 3 tanggungan
            default => 54000000,
        };

        // Biaya jabatan (5% dari penghasilan bruto, max 6jt/tahun)
        $positionCost = min($annualGross * 0.05, 6000000);

        // Penghasilan neto per tahun
        $netIncome = $annualGross - $positionCost;

        // PKP (Penghasilan Kena Pajak)
        $pkp = max(0, $netIncome - $ptkp);

        // Calculate PPh21 with progressive rates
        $pph21Annual = 0;
        $remainingPkp = $pkp;

        // Tarif progresif PPh21 (UU HPP)
        $brackets = [
            [60000000, 0.05],      // 5% untuk 0 - 60 juta
            [190000000, 0.15],     // 15% untuk 60 - 250 juta
            [250000000, 0.25],     // 25% untuk 250 - 500 juta
            [4500000000, 0.30],    // 30% untuk 500 juta - 5 miliar
            [PHP_FLOAT_MAX, 0.35], // 35% untuk > 5 miliar
        ];

        $previousLimit = 0;
        foreach ($brackets as [$limit, $rate]) {
            if ($remainingPkp <= 0) break;

            $bracketAmount = min($remainingPkp, $limit - $previousLimit);
            $pph21Annual += $bracketAmount * $rate;
            $remainingPkp -= $bracketAmount;
            $previousLimit = $limit;
        }

        // Jika tidak punya NPWP, tambah 20%
        if (!$hasNpwp) {
            $pph21Annual *= 1.2;
        }

        // PPh21 per bulan
        $pph21Monthly = $pph21Annual / 12;

        // Take home pay
        $takeHomePay = $grossIncome - $pph21Monthly;

        $result = [
            'gross_income' => $grossIncome,
            'annual_gross' => $annualGross,
            'position_cost' => $positionCost,
            'net_income' => $netIncome,
            'ptkp' => $ptkp,
            'pkp' => $pkp,
            'pph21_annual' => $pph21Annual,
            'pph21_monthly' => $pph21Monthly,
            'take_home_pay' => $takeHomePay,
            'effective_rate' => $grossIncome > 0 ? ($pph21Monthly / $grossIncome) * 100 : 0,
        ];

        return view('calculator.pph21', compact('result'))
            ->withInput($request->all());
    }
}
