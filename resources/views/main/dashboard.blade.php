@extends('layout.pemilik')

@section('title', 'Dashboard')

@section('content')
<div class="p-4 space-y-6">
    <!-- Monthly Sales -->
    <div class="bg-white rounded-2xl shadow p-4 w-full">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Monthly Sales</h2>
        <div class="chart-container h-64">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

    <!-- Daily & Weekly -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Daily Sales -->
        <div class="bg-white rounded-2xl shadow p-4">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Daily Sales (Last 7 Days)</h2>
            <div class="chart-container h-64">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>

        <!-- Weekly Sales -->
        <div class="bg-white rounded-2xl shadow p-4">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Weekly Sales (Last 4 Weeks)</h2>
            <div class="chart-container h-64">
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// Monthly income: normalize to full 12 months (Jan to Dec)
const rawMonthly = @json($monthlyIncome->toArray());
const monthlyIncome = Array.from({
    length: 12
}, (_, i) => rawMonthly[i + 1] || 0);

// Daily
const rawDailyIncome = @json($dailyIncome->toArray());
const dailyLabels = [];
const dailyData = [];
for (let i = 6; i >= 0; i--) {
    const date = new Date();
    date.setDate(date.getDate() - i);
    const isoDate = date.toISOString().slice(0, 10);
    dailyLabels.push(isoDate);
    dailyData.push(rawDailyIncome[isoDate] ?? 0);
}

// Weekly
const rawWeeklyIncome = @json($weeklyIncome);
const weeklyLabels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
const weeklyData = weeklyLabels.map((_, idx) => rawWeeklyIncome[idx + 1] ?? 0);

</script>

<!-- Chart.js CDN (jika belum ada) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Chart Initialization -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Chart
    new Chart(document.getElementById('monthlyChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul',
                'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ],
            datasets: [{
                label: 'Monthly Income',
                data: monthlyIncome,
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => 'Rp' + value.toLocaleString('id-ID')
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: context => 'Income: Rp' + context.raw.toLocaleString('id-ID')
                    }
                }
            }
        }
    });

    // Daily Chart
    new Chart(document.getElementById('dailyChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Daily Income',
                data: dailyData,
                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 2,
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => 'Rp' + value.toLocaleString('id-ID')
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: context => 'Income: Rp' + context.raw.toLocaleString('id-ID')
                    }
                }
            }
        }
    });

    // Weekly Chart
    new Chart(document.getElementById('weeklyChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: weeklyLabels,
            datasets: [{
                label: 'Weekly Income',
                data: weeklyData,
                backgroundColor: 'rgba(245, 158, 11, 0.7)',
                borderColor: 'rgba(245, 158, 11, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => 'Rp' + value.toLocaleString('id-ID')
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: context => 'Income: Rp' + context.raw.toLocaleString('id-ID')
                    }
                }
            }
        }
    });
});
</script>
@endsection