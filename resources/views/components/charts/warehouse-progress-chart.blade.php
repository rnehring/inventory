 {{-- Warehouse Progress - Radial Bar Chart --}}
<div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-4">
        <h5 class="text-xl font-bold text-gray-900 dark:text-white">
            <x-ri-building-2-fill class="w-6 h-6 inline-block mr-2 text-cyan-500"/>
            Warehouse Completion Progress
        </h5>
    </div>

    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
        Real-time completion percentage by warehouse location
    </p>

    <div id="warehouse-progress-chart"></div>

    <div class="mt-4 space-y-2 flex flex-row flex-wrap justify-stretch items-end gap-2" id="warehouse-details">
        <!-- Warehouse details populated by JS -->
    </div>
</div>

 @push('scripts')
<script>
function plantCodeToName(plantCode){
    switch(plantCode) {
        case "P1-RAW":
            return "Plant 1 - Raw";
            break;
        case "P1-QUAL":
            return "Plant 1 - Quality";
            break;
        case "P1-PROD":
            return "Plant 1 - Prod";
            break;
        case "P2-RAW":
            return "Plant 2 - Raw";
            break;
        case "P2-QUAL":
            return "Plant 2 - Quality";
            break;
        case "P2-PROD":
            return "Plant 2 - Prod";
            break;
        case "P3-RAW":
            return "Plant 3 - Raw";
            break;
        case "P3-QUAL":
            return "Plant 3 - Quality";
            break;
        case "P3-PROD":
            return "Plant 3 - Prod";
            break;
        case "P4-RAW":
            return "Houston";
            break;
        case "P4-SHIP":
            return "Houston - Shipping";
            break;
        default:
            "Plant 1";
            break;
    }
}
document.addEventListener('DOMContentLoaded', function () {
    fetch('/warehouse-progress')
        .then(res => res.json())
        .then(data => {
            console.log('Warehouse progress data:', data);

            if (data.length === 0) {
                document.getElementById('warehouse-progress-chart').innerHTML =
                    '<p class="text-gray-500 text-center py-8">No warehouse data available</p>';
                return;
            }

            const warehouses = data.map(d => plantCodeToName(d.warehouse));
            const percentages = data.map(d => parseFloat(d.completion_percent) || 0);

            // Color mapping for warehouses
            const colorMap = {
                'Plant 1 - Raw': '#06b6d4',
                'Plant 2 - Raw': '#be4a4d',
                'Plant 3 - Raw': '#8b5cf6',
                'Houston': '#10b981',
                'Default': '#f59e0b'
            };

            const colors = warehouses.map(w => colorMap[w] || colorMap['Default']);

            const options = {
                series: percentages,
                labels: warehouses,
                chart: {
                    height: 350,
                    type: 'radialBar',
                    background: 'transparent',
                    toolbar: { show: false }
                },
                plotOptions: {
                    radialBar: {
                        offsetY: 0,
                        startAngle: 0,
                        endAngle: data.length === 1 ? 360 : 270,  // Full circle if only 1 warehouse
                        hollow: {
                            margin: 5,
                            size: '30%',
                            background: 'transparent'
                        },
                        dataLabels: {
                            name: {
                                show: true,
                                fontSize: '16px',
                                color: '#ffffff',
                                offsetY: -10
                            },
                            value: {
                                show: true,
                                fontSize: '28px',
                                fontWeight: 'bold',
                                color: '#fff',
                                offsetY: 5,
                                formatter: function(val) {
                                    return val.toFixed(1) + '%';
                                }
                            },
                            total: {
                            show: data.length > 1,  // Only show total if multiple warehouses
                            label: 'Overall',
                            fontSize: '14px',
                            color: '#ffffff',
                            formatter: function (w) {
                                    const avg = w.globals.seriesTotals.reduce((a, b) => a + b, 0) / w.globals.seriesTotals.length;
                                    return avg.toFixed(1) + '%';
                                }
                            }
                        },
                        track: {
                            background: '#374151',
                            strokeWidth: '97%',
                            margin: 5
                        }
                    }
                },
                colors: colors,
                legend: {
                    show: true,
                    position: 'bottom',
                    labels: {
                        colors: '#ffffff',
                        useSeriesColors: false
                    },
                    fontSize: '14px',
                    fontWeight: 500,
                    markers: {
                        width: 12,
                        height: 12,
                        radius: 12
                    },
                    itemMargin: {
                        horizontal: 10,
                        vertical: 5
                    }
                },
                stroke: {
                    lineCap: 'round'
                }
            };

            const chart = new ApexCharts(document.querySelector("#warehouse-progress-chart"), options);
            chart.render();

            // Add warehouse details
            const detailsContainer = document.getElementById('warehouse-details');
            data.forEach((warehouse, idx) => {
                const div = document.createElement('div');
                div.className = 'flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg w-49';
                div.innerHTML = `
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-3" style="background-color: ${colors[idx]}"></div>
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-white">${plantCodeToName(warehouse.warehouse)}</div>
                            <div class="text-xs text-gray-500">${parseInt(warehouse.counted_parts).toLocaleString()} / ${parseInt(warehouse.total_parts).toLocaleString()} parts</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-lg" style="color: ${colors[idx]}">${parseFloat(warehouse.completion_percent).toFixed(1)}%</div>
                        <div class="text-xs text-gray-500">
                            ${new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', notation: 'compact', maximumFractionDigits: 1 }).format(warehouse.counted_value)} counted
                        </div>
                    </div>
                `;
                detailsContainer.appendChild(div);
            });
        })
        .catch(error => {
            console.error('Error loading warehouse progress chart:', error);
            document.getElementById('warehouse-progress-chart').innerHTML =
                '<p class="text-red-500 text-center py-8">Error loading chart</p>';
        });
});
</script>
@endpush
