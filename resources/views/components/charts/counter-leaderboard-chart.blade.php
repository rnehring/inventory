{{-- Counter Leaderboard - Bar Chart --}}
<div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-4">
        <h5 class="text-xl font-bold text-gray-900 dark:text-white">
            <x-ri-trophy-fill class="w-6 h-6 inline-block mr-2 text-yellow-500"/>
            Counter Leaderboard
        </h5>
    </div>

    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
        Top 10 performers by parts counted - motivate your team!
    </p>

    <div id="leaderboard-chart"></div>

    <div class="grid grid-cols-3 gap-4 mt-4 text-sm">
        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg text-center">
            <div class="text-2xl mb-1">🥇</div>
            <div class="text-xs text-gray-600 dark:text-gray-400">1st Place</div>
            <div class="font-bold text-yellow-600 dark:text-yellow-400" id="first-place">-</div>
            <div class="text-xs text-gray-500" id="first-count">0 parts</div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-900/20 p-3 rounded-lg text-center">
            <div class="text-2xl mb-1">🥈</div>
            <div class="text-xs text-gray-600 dark:text-gray-400">2nd Place</div>
            <div class="font-bold text-gray-600 dark:text-gray-400" id="second-place">-</div>
            <div class="text-xs text-gray-500" id="second-count">0 parts</div>
        </div>
        <div class="bg-orange-50 dark:bg-orange-900/20 p-3 rounded-lg text-center">
            <div class="text-2xl mb-1">🥉</div>
            <div class="text-xs text-gray-600 dark:text-gray-400">3rd Place</div>
            <div class="font-bold text-orange-600 dark:text-orange-400" id="third-place">-</div>
            <div class="text-xs text-gray-500" id="third-count">0 parts</div>
        </div>
    </div>
</div>

<script>
    import ApexCharts from 'apexcharts';
document.addEventListener('DOMContentLoaded', function () {
    fetch('/counter-leaderboard')
        .then(res => res.json())
        .then(data => {
            console.log('Leaderboard data:', data);

            if (data.length === 0) {
                document.getElementById('leaderboard-chart').innerHTML =
                    '<div class="text-center py-8">' +
                    '<p class="text-gray-500 mb-2">No counting activity yet!</p>' +
                    '<p class="text-sm text-gray-400">Start counting to see leaderboard</p>' +
                    '</div>';
                return;
            }

            const names = data.map(d => d.name.trim() || 'Unknown');
            const counts = data.map(d => parseInt(d.parts_counted) || 0);
            const values = data.map(d => parseFloat(d.value_counted) || 0);

            // Update podium
            if (data.length >= 1) {
                const name1 = data[0].name.trim() || 'Unknown';
                document.getElementById('first-place').textContent = name1;
                document.getElementById('first-count').textContent = (data[0].parts_counted || 0).toLocaleString() + ' parts';
            }
            if (data.length >= 2) {
                const name2 = data[1].name.trim() || 'Unknown';
                document.getElementById('second-place').textContent = name2;
                document.getElementById('second-count').textContent = (data[1].parts_counted || 0).toLocaleString() + ' parts';
            }
            if (data.length >= 3) {
                const name3 = data[2].name.trim() || 'Unknown';
                document.getElementById('third-place').textContent = name3;
                document.getElementById('third-count').textContent = (data[2].parts_counted || 0).toLocaleString() + ' parts';
            }

            // Generate rainbow gradient colors
            const colors = counts.map((val, idx) => {
                const hue = (idx * 360 / Math.max(counts.length, 10));
                return `hsl(${hue}, 70%, 60%)`;
            });

            const options = {
                series: [{
                    name: 'Parts Counted',
                    data: counts
                }],
                chart: {
                    type: 'bar',
                    height: 400,
                    background: 'transparent',
                    toolbar: { show: false },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        horizontal: true,
                        distributed: true,
                        barHeight: data.length === 1 ? '30%' : '75%',
                        dataLabels: {
                            position: 'top'
                        }
                    }
                },
                colors:  ['#cbd7a5', '#a4dbcc', '#009add', '#848254', '#eaeae1'],
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val.toLocaleString();
                    },
                    offsetX: 30,
                    style: {
                        fontSize: '12px',
                        fontWeight: 'bold',
                        colors: ['#fff']
                    }
                },
                xaxis: {
                    categories: names,
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '11px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: colors,
                            fontSize: '12px',
                            fontWeight: 'bold'
                        }
                    }
                },
                grid: {
                    borderColor: '#374151',
                    strokeDashArray: 4,
                    xaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                tooltip: {
                    theme: 'light',
                    custom: function({ series, seriesIndex, dataPointIndex, w }) {
                        const counter = data[dataPointIndex];
                        return '<div class="p-3">' +
                            '<div class="font-bold mb-1">' + (counter.name.trim() || 'Unknown') + '</div>' +
                            '<div>Parts: ' + (counter.parts_counted || 0).toLocaleString() + '</div>' +
                            '<div>Value: ' + new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(counter.value_counted || 0) + '</div>' +
                            '<div>Days Active: ' + (counter.days_active || 0) + '</div>' +
                            '</div>';
                    }
                },
                legend: {
                    show: false
                }
            };

            const chart = new ApexCharts(document.querySelector("#leaderboard-chart"), options);
            chart.render();
        })
        .catch(error => {
            console.error('Error loading leaderboard chart:', error);
            document.getElementById('leaderboard-chart').innerHTML =
                '<p class="text-red-500 text-center py-8">Error loading chart</p>';
        });
});
</script>
