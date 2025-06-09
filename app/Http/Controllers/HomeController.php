<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $driver = DB::getDriverName();

        // Monthly Income (this year)
        $monthlyIncomeRaw = Sale::selectRaw(
                $driver === 'sqlite'
                    ? "strftime('%m', created_at) as month, SUM(price) as total"
                    : "MONTH(created_at) as month, SUM(price) as total"
            )
            ->when($driver !== 'sqlite', fn($q) => $q->whereYear('created_at', now()->year))
            ->groupBy('month')
            ->pluck('total', 'month');

        // Lengkapi data bulanan dari 1 sampai 12 (bulan tanpa data = 0)
        $monthlyIncome = collect(range(1, 12))->mapWithKeys(function ($month) use ($monthlyIncomeRaw) {
            // Key di $monthlyIncomeRaw kemungkinan string "01", "02" (sqlite) atau integer 1,2 (MySQL)
            $key = str_pad($month, 2, '0', STR_PAD_LEFT); // pad bulan jadi "01", "02", dll

            // Cek ada di raw, jika tidak ada pakai 0
            if ($monthlyIncomeRaw->has($month)) {
                return [$month => $monthlyIncomeRaw[$month]];
            } elseif ($monthlyIncomeRaw->has($key)) {
                return [$month => $monthlyIncomeRaw[$key]];
            }
            return [$month => 0];
        });

        // Daily Income (last 7 days)
        $dailyIncome = Sale::selectRaw(
                $driver === 'sqlite'
                    ? "strftime('%Y-%m-%d', created_at) as date, SUM(price) as total"
                    : "DATE(created_at) as date, SUM(price) as total"
            )
            ->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->groupBy('date')
            ->pluck('total', 'date');

        // Weekly Income (last 4 weeks, hitung berdasarkan minggu dalam bulan ini)
        // Misal: minggu ke-1 sampai ke-4 dalam bulan ini
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $weeklyIncomeRaw = Sale::selectRaw(
            $driver === 'sqlite'
                ? "strftime('%W', created_at) as week_number, SUM(price) as total"
                : "WEEK(created_at, 1) as week_number, SUM(price) as total"
        )
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->groupBy('week_number')
        ->pluck('total', 'week_number');

        // Hitung minggu dalam bulan ini dari 1 sampai max 4
        // Untuk minggu dalam bulan, hitung minggu keberapa dalam bulan
        $weeksInMonth = [];
        for ($i = 0; $i < 4; $i++) {
            $weekStart = $startOfMonth->copy()->addWeeks($i)->startOfWeek();
            $weekEnd = $startOfMonth->copy()->addWeeks($i)->endOfWeek();

            if ($weekStart->month !== $now->month && $weekEnd->month !== $now->month) {
                break; // tidak masuk bulan ini
            }

            $weeksInMonth[] = $weekStart->weekOfYear;
        }

        $weeklyIncome = collect($weeksInMonth)->mapWithKeys(function ($weekNumber, $index) use ($weeklyIncomeRaw) {
            // index + 1 sebagai label minggu ke-1, ke-2, dst
            return [($index + 1) => $weeklyIncomeRaw[$weekNumber] ?? 0];
        });

        return view('main.dashboard', compact('monthlyIncome', 'dailyIncome', 'weeklyIncome'));
    }
}
