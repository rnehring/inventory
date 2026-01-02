{{-- Warehouse Comparison Chart --}}
<div class="mt-6">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-xl font-bold text-gray-900 dark:text-white">
                <x-ri-building-fill class="w-6 h-6 inline-block mr-2 text-orange-500"/>
                Warehouse-by-Warehouse Analysis
            </h5>
        </div>

        <div id="warehouseChart" class="w-11/12 mx-auto bg-transparent"></div>
        <div class="p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Detailed Warehouse Breakdown</h3>
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Warehouse</th>
                        <th scope="col" class="px-6 py-3 text-right">Total Items</th>
                        <th scope="col" class="px-6 py-3 text-right">Surprise Finds</th>
                        <th scope="col" class="px-6 py-3 text-right">Missing Stock</th>
                        <th scope="col" class="px-6 py-3 text-right">Accurate</th>
                        <th scope="col" class="px-6 py-3 text-right">Reliability %</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($warehouseData as $wh)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $wh['warehouse'] }}
                            </th>
                            <td class="px-6 py-4 text-right">{{ number_format($wh['total']) }}</td>
                            <td class="px-6 py-4 text-right">
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">
                                                {{ number_format($wh['surpriseFinds']) }}
                                            </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                                                {{ number_format($wh['unexpectedEmpty']) }}
                                            </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                                {{ number_format($wh['accurate']) }}
                                            </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                            <span class="text-lg font-bold text-blue-600">
                                                {{ $wh['total'] > 0 ? number_format(($wh['accurate'] / $wh['total']) * 100, 1) : '0' }}%
                                            </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Warehouse Details Table --}}
<div class="mt-6">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Warehouse Comparison Chart
            var warehouseOptions = {
                series: [{
                    name: 'Surprise Finds',
                    data: @json(array_column($warehouseData, 'surpriseFinds'))
                }, {
                    name: 'Missing Stock',
                    data: @json(array_column($warehouseData, 'unexpectedEmpty'))
                }, {
                    name: 'Accurate',
                    data: @json(array_column($warehouseData, 'accurate'))
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true,
                    background: 'transparent',
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: true
                    }
                },
                colors: ['#F59E0B', '#EF4444', '#10B981'],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        legend: {
                            position: 'bottom',
                            offsetX: -10,
                            offsetY: 0
                        }
                    }
                }],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 8,
                        dataLabels: {
                            total: {
                                enabled: true,
                                style: {
                                    fontSize: '13px',
                                    fontWeight: 900
                                }
                            }
                        }
                    },
                },
                xaxis: {
                    categories: @json(array_column($warehouseData, 'warehouse')),
                    labels: {
                        style: {
                            colors: '#ffffff'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Number of Items',
                        style: {
                            color: '#ffffff'
                        }
                    },
                    labels: {
                        style: {
                            colors: '#ffffff'
                        }
                    }
                },
                legend: {
                    position: 'top',
                    offsetY: 0,
                    labels: {
                        colors: '#ffffff'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    theme: 'light',
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Inter, sans-serif'
                    },
                    y: {
                        formatter: function (val) {
                            return val + " items"
                        }
                    }
                }
            };

            var warehouseChart = new ApexCharts(document.querySelector("#warehouseChart"), warehouseOptions);
            warehouseChart.render();
        });
    </script>
@endpush
