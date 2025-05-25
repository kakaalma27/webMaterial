@extends('layout.pemilik')

@section('title', 'Dashboard')

@section('content')
<div class="p-4 space-y-6">
    <!-- Monthly Sales Full Width -->
    <div class="bg-white rounded-2xl shadow p-4 w-full">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Monthly Sales</h2>
        <div class="chart-container h-64">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

    <!-- Daily & Weekly Side by Side -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Daily Sales -->
        <div class="bg-white rounded-2xl shadow p-4">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Daily Sales (Last 7 Days)</h2>
            <div class="chart-container">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>

        <!-- Weekly Sales -->
        <div class="bg-white rounded-2xl shadow p-4">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Weekly Sales (Last 4 Weeks)</h2>
            <div class="chart-container">
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                'Dec'
            ],
            datasets: [{
                label: 'Monthly Income',
                data: [12000, 19000, 15000, 18000, 22000, 25000, 23000, 21000, 24000, 26000,
                    28000, 30000
                ],
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
                        callback: function(value) {
                            return 'Rp' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Income: Rp' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Daily Chart (Last 7 days)
    const dailyCtx = document.getElementById('dailyChart').getContext('2d');
    const dailyChart = new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Daily Income',
                data: [900, 1200, 800, 1100, 1500, 2000, 1800],
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
                        callback: function(value) {
                            return 'Rp' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Income: Rp' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Weekly Chart (Last 4 weeks)
    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    const weeklyChart = new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Weekly Income',
                data: [8500, 9200, 7800, 10500],
                backgroundColor: [
                    'rgba(245, 158, 11, 0.7)',
                    'rgba(234, 88, 12, 0.7)',
                    'rgba(220, 38, 38, 0.7)',
                    'rgba(139, 92, 246, 0.7)'
                ],
                borderColor: [
                    'rgba(245, 158, 11, 1)',
                    'rgba(234, 88, 12, 1)',
                    'rgba(220, 38, 38, 1)',
                    'rgba(139, 92, 246, 1)'
                ],
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
                        callback: function(value) {
                            return 'Rp' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Income: Rp' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection