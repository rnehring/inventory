{{-- Count Velocity Timeline --}}
<div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-4">
        <h5 class="text-xl font-bold text-gray-900 dark:text-white">
            <x-ri-line-chart-fill class="w-6 h-6 inline-block mr-2 text-purple-500"/>
            Count Velocity (Last 30 Days)
        </h5>
    </div>

    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
        Daily counting progress with trend line - shows momentum and predicts completion
    </p>

    <div id="velocity-chart"></div>

    <div class="grid grid-cols-3 gap-4 mt-4 text-sm">
        <div class="bg-purple-50 dark:bg-purple-900/20 p-3 rounded-lg text-center">
            <div class="text-gray-600 dark:text-gray-400 text-xs">Total Counted</div>
            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400" id="total-counted">0</div>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg text-center">
            <div class="text-gray-600 dark:text-gray-400 text-xs">Avg Per Day</div>
            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="avg-per-day">0</div>
        </div>
        <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg text-center">
            <div class="text-gray-600 dark:text-gray-400 text-xs">Peak Day</div>
            <div class="text-2xl font-bold text-green-600 dark:text-green-400" id="peak-day">0</div>
        </div>
    </div>
</div>

<script>
    import ApexCharts from 'apexcharts';
document.addEventListener('DOMContentLoaded', function () {
    fetch('/count-velocity')
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                document.getElementById('total-counted').textContent = '0';
                document.getElementById('avg-per-day').textContent = '0';
                document.getElementById('peak-day').textContent = '0';
                return;
            }

            const dates = data.map(d => d.count_date);
            const counts = data.map(d => parseInt(d.parts_counted) || 0);

            // Calculate stats
            const totalCounted = counts.reduce((a, b) => a + b, 0);
            const avgPerDay = Math.round(totalCounted / counts.length);
            const peakDay = Math.max(...counts);

            // Update summary cards
            document.getElementById('total-counted').textContent = totalCounted.toLocaleString();
            document.getElementById('avg-per-day').textContent = avgPerDay.toLocaleString();
            document.getElementById('peak-day').textContent = peakDay.toLocaleString();

            // Create chart
            const options = {
                series: [{
                    name: 'Parts Counted',
                    data: counts
                }],
                chart: {
                    height: 350,
                    type: 'area',
                    background: 'transparent',
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                colors: ['#8b5cf6'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.2,
                        stops: [0, 90, 100]
                    }
                },
                xaxis: {
                    categories: dates,
                    type: 'datetime',
                    labels: {
                        format: 'MMM dd',
                        style: {
                            colors: '#ffffff'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Parts Counted',
                        style: { color: '#ffffff' }
                    },
                    labels: {
                        style: { colors: '#ffffff' }
                    }
                },
                grid: {
                    borderColor: '#374151',
                    strokeDashArray: 4
                },
                tooltip: {
                    theme: 'light',
                    x: {
                        format: 'MMM dd, yyyy'
                    },
                    y: {
                        formatter: function(val) {
                            return val + ' parts';
                        }
                    }
                },
                markers: {
                    size: 5,
                    colors: ['#8b5cf6'],
                    strokeColors: '#1f2937',
                    strokeWidth: 2,
                    hover: {
                        size: 7
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#velocity-chart"), options);
            chart.render();
        })
        .catch(error => console.error('Error loading velocity chart:', error));
});
</script>
