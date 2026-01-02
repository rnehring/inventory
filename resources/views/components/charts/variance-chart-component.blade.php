{{-- Variance Distribution Chart --}}
<div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-4">
        <h5 class="text-xl font-bold text-gray-900 dark:text-white">
            <x-ri-pie-chart-2-fill class="w-6 h-6 inline-block mr-2 text-green-500"/>
            Count Accuracy Distribution
        </h5>
    </div>

    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
        Color-coded zones show count accuracy - Green is excellent, Red needs attention
    </p>

    <div id="variance-chart"></div>

    <div class="grid grid-cols-3 gap-2 mt-4 text-xs">
        <div class="bg-green-50 dark:bg-green-900/20 p-2 rounded text-center">
            <div class="text-green-600 dark:text-green-400 font-bold" id="excellent-count">0</div>
            <div class="text-gray-600 dark:text-gray-400">Excellent</div>
        </div>
        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-2 rounded text-center">
            <div class="text-yellow-600 dark:text-yellow-400 font-bold" id="good-count">0</div>
            <div class="text-gray-600 dark:text-gray-400">Good</div>
        </div>
        <div class="bg-red-50 dark:bg-red-900/20 p-2 rounded text-center">
            <div class="text-red-600 dark:text-red-400 font-bold" id="poor-count">0</div>
            <div class="text-gray-600 dark:text-gray-400">Needs Recount</div>
        </div>
    </div>
</div>

<script>
    import ApexCharts from 'apexcharts';
document.addEventListener('DOMContentLoaded', function () {
    fetch('/variance-distribution')
        .then(res => res.json())
        .then(data => {
            const labels = data.map(d => d.accuracy_range);
            const counts = data.map(d => parseInt(d.count) || 0);

            // Update summary counts
            const excellent = data.find(d => d.accuracy_range === 'Excellent (±0-5%)');
            const good = data.find(d => d.accuracy_range === 'Good (±5-10%)');
            const fair = data.find(d => d.accuracy_range === 'Fair (±10-25%)');
            const poor = data.find(d => d.accuracy_range === 'Poor (±25%+)');

            if (excellent) document.getElementById('excellent-count').textContent = excellent.count;
            if (good) document.getElementById('good-count').textContent = good.count;
            if (poor || fair) {
                const poorCount = (poor ? parseInt(poor.count) : 0) + (fair ? parseInt(fair.count) : 0);
                document.getElementById('poor-count').textContent = poorCount;
            }

            // Color mapping
            const colors = labels.map(label => {
                if (label.includes('Excellent')) return '#10b981'; // Green
                if (label.includes('Good')) return '#3b82f6';      // Blue
                if (label.includes('Fair')) return '#f59e0b';      // Orange
                if (label.includes('Poor')) return '#ef4444';      // Red
                return '#6b7280'; // Gray for Not Counted
            });

            const options = {
                series: counts,
                labels: labels,
                chart: {
                    type: 'donut',
                    height: 350,
                    background: 'transparent',
                    toolbar: { show: false }
                },
                colors: colors,
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '14px',
                                    color: '#ffffff'
                                },
                                value: {
                                    show: true,
                                    fontSize: '22px',
                                    fontWeight: 'bold',
                                    color: '#fff',
                                    formatter: function(val) {
                                        return val;
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'Total Parts',
                                    fontSize: '14px',
                                    color: '#ffffff',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    }
                                }
                            }
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        colors: '#ffffff'
                    }
                },
                tooltip: {
                    theme: 'light',
                    y: {
                        formatter: function(val) {
                            return val + ' parts';
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val.toFixed(1) + '%';
                    },
                    style: {
                        fontSize: '12px',
                        colors: ['#fff']
                    },
                    dropShadow: {
                        enabled: false
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#variance-chart"), options);
            chart.render();
        })
        .catch(error => console.error('Error loading variance chart:', error));
});
</script>
