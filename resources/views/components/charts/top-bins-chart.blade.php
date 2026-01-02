{{-- Top Bins by Value - Horizontal Bar Chart --}}
<div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-4">
        <h5 class="text-xl font-bold text-gray-900 dark:text-white">
            <x-ri-inbox-fill class="w-6 h-6 inline-block mr-2 text-orange-500"/>
            Top 15 Bins by Value
        </h5>
    </div>

    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
        Shows the most valuable bin locations - focus counting efforts here first
    </p>

    <div id="top-bins-chart"></div>

    <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
        <div class="bg-orange-50 dark:bg-orange-900/20 p-3 rounded-lg">
            <div class="text-gray-600 dark:text-gray-400">Top Bin Value</div>
            <div class="text-2xl font-bold text-orange-600 dark:text-orange-400" id="top-bin-value">$0</div>
            <div class="text-xs text-gray-500" id="top-bin-name">-</div>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
            <div class="text-gray-600 dark:text-gray-400">Total Top 15 Value</div>
            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="total-top-bins">$0</div>
            <div class="text-xs text-gray-500" id="total-top-parts">0 parts</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('/top-bins')
        .then(res => res.json())
        .then(data => {
            console.log('Top bins data:', data);

            if (data.length === 0) {
                document.getElementById('top-bins-chart').innerHTML = '<p class="text-gray-500 text-center py-8">No bin data available</p>';
                return;
            }

            // Prepare data - reverse so highest is on top
            const bins = data.map(d => `${d.bin} (${d.warehouse})`).reverse();
            const values = data.map(d => parseFloat(d.total_value) || 0).reverse();
            const partCounts = data.map(d => parseInt(d.part_count) || 0).reverse();
            const countedParts = data.map(d => parseInt(d.counted_parts) || 0).reverse();

            // Calculate summary stats
            const topBinValue = data[0].total_value;
            const topBinName = `${data[0].bin} (${data[0].warehouse})`;
            const totalValue = data.reduce((sum, item) => sum + parseFloat(item.total_value), 0);
            const totalParts = data.reduce((sum, item) => sum + parseInt(item.part_count), 0);

            // Update summary cards
            document.getElementById('top-bin-value').textContent =
                new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 }).format(topBinValue);
            document.getElementById('top-bin-name').textContent = topBinName;
            document.getElementById('total-top-bins').textContent =
                new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 }).format(totalValue);
            document.getElementById('total-top-parts').textContent = `${totalParts} parts`;

            const options = {
                series: [{
                    name: 'Total Value',
                    data: values
                }],
                chart: {
                    type: 'bar',
                    height: 500,
                    background: 'transparent',
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: true,
                        barHeight: '70%',
                        distributed: true
                    }
                },
                colors: ['#cbd7a5', '#a4dbcc', '#009add', '#848254', '#eaeae1'],
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        if (val >= 1000000) {
                            return '$' + (val / 1000000).toFixed(1) + 'M';
                        } else if (val >= 1000) {
                            return '$' + (val / 1000).toFixed(0) + 'K';
                        }
                        return '$' + val.toFixed(0);
                    },
                    offsetX: 30,
                    style: {
                        fontSize: '11px',
                        colors: ['#fff']
                    }
                },
                xaxis: {
                    categories: bins,
                    labels: {
                        formatter: function(val) {
                            if (val >= 1000000) {
                                return '$' + (val / 1000000).toFixed(1) + 'M';
                            } else if (val >= 1000) {
                                return '$' + (val / 1000).toFixed(0) + 'K';
                            }
                            return '$' + val.toFixed(0);
                        },
                        style: {
                            colors: '#ffffff'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '11px'
                        },
                        maxWidth: 120
                    }
                },
                grid: {
                    borderColor: '#374151',
                    strokeDashArray: 4,
                    xaxis: {
                        lines: {
                            show: true
                        }
                    },
                    yaxis: {
                        lines: {
                            show: false
                        }
                    }
                },
                tooltip: {
                    theme: 'light',
                    custom: function({ series, seriesIndex, dataPointIndex, w }) {
                        const reversedIndex = data.length - 1 - dataPointIndex;
                        const item = data[reversedIndex];
                        return '<div class="p-3">' +
                            '<div class="font-bold mb-1">' + item.bin + ' (' + item.warehouse + ')</div>' +
                            '<div>Value: ' + new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(item.total_value) + '</div>' +
                            '<div>Parts: ' + item.part_count + '</div>' +
                            '<div>Counted: ' + item.counted_parts + '/' + item.part_count + '</div>' +
                            '</div>';
                    }
                },
                legend: {
                    show: false
                }
            };

            const chart = new ApexCharts(document.querySelector("#top-bins-chart"), options);
            chart.render();
        })
        .catch(error => {
            console.error('Error loading top bins chart:', error);
            document.getElementById('top-bins-chart').innerHTML = '<p class="text-red-500 text-center py-8">Error loading chart</p>';
        });
});
</script>
@endpush
