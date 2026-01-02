{{-- ABC Analysis / Pareto Chart --}}
<div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-4">
        <h5 class="text-xl font-bold text-gray-900 dark:text-white">
            <x-ri-bar-chart-2-fill class="w-6 h-6 inline-block mr-2 text-blue-500"/>
            ABC Analysis (Pareto Principle)
        </h5>
    </div>

    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
        Shows that 20% of parts represent 80% of inventory value - validates prioritization strategy
    </p>

    <div id="abc-chart"></div>

    <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
        <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
            <div class="text-gray-600 dark:text-gray-400">A Items (Top 20%)</div>
            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="a-items-value">$0</div>
            <div class="text-xs text-gray-500" id="a-items-count">0 parts</div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-900/20 p-3 rounded-lg">
            <div class="text-gray-600 dark:text-gray-400">B/C Items (Bottom 80%)</div>
            <div class="text-2xl font-bold text-gray-600 dark:text-gray-400" id="bc-items-value">$0</div>
            <div class="text-xs text-gray-500" id="bc-items-count">0 parts</div>
        </div>
    </div>
</div>

<script>
import ApexCharts from 'apexcharts';

document.addEventListener('DOMContentLoaded', function () {
    fetch('/abc-analysis')
        .then(res => res.json())
        .then(data => {
            const categories = data.map(d => d.category);
            const values = data.map(d => parseFloat(d.total_value) || 0);
            const counts = data.map(d => parseInt(d.part_count) || 0);
            const percentCounted = data.map(d => parseFloat(d.percent_counted) || 0);

            // Update summary cards
            const aData = data.find(d => d.category === 'A Items (Top 20%)');
            const bcData = data.find(d => d.category === 'B/C Items (Bottom 80%)');

            if (aData) {
                document.getElementById('a-items-value').textContent =
                    new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 }).format(aData.total_value);
                document.getElementById('a-items-count').textContent = `${aData.part_count} parts`;
            }

            if (bcData) {
                document.getElementById('bc-items-value').textContent =
                    new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 }).format(bcData.total_value);
                document.getElementById('bc-items-count').textContent = `${bcData.part_count} parts`;
            }

            // Create chart
            const options = {
                series: [{
                    name: 'Total Value',
                    type: 'column',
                    data: values
                }, {
                    name: 'Percent Counted',
                    type: 'line',
                    data: percentCounted
                }],
                chart: {
                    height: 350,
                    type: 'line',
                    background: 'transparent',
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '60%',
                        borderRadius: 8
                    }
                },
                stroke: {
                    width: [0, 4],
                    curve: 'smooth'
                },
                colors: ['#3b82f6', '#10b981'],
                xaxis: {
                    categories: categories,
                    labels: {
                        style: {
                            colors: '#ffffff'
                        }
                    }
                },
                yaxis: [{
                    title: {
                        text: 'Total Value ($)',
                        style: { color: '#ffffff' }
                    },
                    labels: {
                        formatter: function(val) {
                            return '$' + (val / 1000).toFixed(0) + 'K';
                        },
                        style: { colors: ['#ffffff'] }
                    }
                }, {
                    opposite: true,
                    title: {
                        text: 'Counted (%)',
                        style: { color: '#ffffff' }
                    },
                    labels: {
                        formatter: function(val) {
                            return val.toFixed(0) + '%';
                        },
                        style: { colors: ['#ffffff'] }
                    }
                }],
                tooltip: {
                    theme: 'light',
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Inter, sans-serif'
                    },
                    y: [{
                        formatter: function(val) {
                            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val);
                        }
                    }, {
                        formatter: function(val) {
                            return val.toFixed(1) + '%';
                        }
                    }]
                },
                legend: {
                    position: 'top',
                    labels: {
                        colors: '#ffffff'
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#abc-chart"), options);
            chart.render();
        })
        .catch(error => console.error('Error loading ABC chart:', error));
});
</script>
