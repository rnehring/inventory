<x-layout>
    <x-slot:header>
        <x-dash-header></x-dash-header>
    </x-slot:header>

    <div class="py-2">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">

            {{-- Business Intelligence Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                @include('components.charts.abc-chart-component')
                @include('components.charts.variance-chart-component')
                @include('components.charts.warehouse-progress-chart')
            </div>

            {{-- Performance Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                @include('components.charts.top-bins-chart')
                @include('components.charts.counter-leaderboard-chart')
            </div>

            {{-- Timeline (Full Width) --}}
            <div class="mt-6">
                @include('components.charts.velocity-chart-component')
            </div>

{{--            <!--- USER COUNT CHART --->--}}
{{--            <div class="w-full bg-neutral-primary-soft flex">--}}

{{--                <div class="flex-1 w-1/3 max-w-2xl border border-default rounded-base shadow dark:bg-gray-800 dark:border-gray-700 border border-gray-200 rounded-lg dark:text-white p-8 m-2 ml-0">--}}

{{--                    <div class="flex justify-between mb-6">--}}
{{--                        <div class="flex items-center">--}}
{{--                            <div>--}}
{{--                                <h5 class="text-xl font-semibold text-heading me-1">Counts By User</h5>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div id="column-chart" class="dark:text-white"></div>--}}
{{--                </div>--}}

{{--                <!--- BRAND CHART --->--}}
{{--                <div class="flex-1 w-1/3 max-w-2xl border border-default rounded-base shadow dark:bg-gray-800 dark:border-gray-700 border border-gray-200 rounded-lg dark:text-white p-8 m-2">--}}

{{--                    <div class="flex justify-between mb-4 md:mb-6">--}}
{{--                        <div class="flex items-center">--}}
{{--                            <div class="flex justify-center items-center">--}}
{{--                                <h5 class="text-xl font-semibold text-heading me-1">Brand Inventory Progress</h5>--}}

{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- Radial Chart -->--}}
{{--                    <div class="py-6" id="radial-chart"></div>--}}
{{--                </div>--}}

{{--                <!--- WAREHOUSE CHART --->--}}
{{--                <div class="flex-1 w-1/3 max-w-2xl border border-default rounded-base shadow dark:bg-gray-800 dark:border-gray-700 border border-gray-200 rounded-lg dark:text-white p-8 m-2">--}}
{{--                    <div class="flex justify-between mb-3">--}}
{{--                        <div class="flex justify-center items-center">--}}
{{--                            <h5 class="text-xl font-semibold text-heading me-1">Value By Warehouse</h5>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <!-- Donut Chart -->--}}
{{--                    <div class="py-6" id="donut-chart"></div>--}}

{{--                </div>--}}

{{--            </div>--}}



            <div class="mt-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-xl font-bold text-gray-900 dark:text-white">
                            <x-ri-numbers-fill class="w-6 h-6 inline-block mr-2 text-blue-500"/>
                            Count Accuracy Breakdown
                        </h5>
                    </div>

                    <div class="flex p-2 flex-row">
                    {{-- Expected Items, Found 0 --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-2 border-red-500 w-6/12 m-2">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Expected Items, Found 0</h4>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Missing stock</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-red-600">
                                        {{ number_format($stats['expectedNonZeroCountedZero']) }}
                                    </div>
                                    <div class="text-sm font-semibold text-gray-600">
                                        {{ $stats['totalItems'] > 0 ? number_format(($stats['expectedNonZeroCountedZero'] / $stats['totalItems']) * 100, 1) : '0' }}%
                                    </div>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-red-600 h-2 rounded-full transition-all duration-500"
                                     style="width: {{ $stats['totalItems'] > 0 ? ($stats['expectedNonZeroCountedZero'] / $stats['totalItems']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Expected Items, Found Items --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-2 border-blue-500 w-6/12 m-2">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Expected Items, Found Items</h4>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">As expected</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ number_format($stats['expectedNonZero'] - $stats['expectedNonZeroCountedZero']) }}
                                    </div>
                                    <div class="text-sm font-semibold text-gray-600">
                                        {{ $stats['totalItems'] > 0 ? number_format((($stats['expectedNonZero'] - $stats['expectedNonZeroCountedZero']) / $stats['totalItems']) * 100, 1) : '0' }}%
                                    </div>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-500"
                                     style="width: {{ $stats['totalItems'] > 0 ? (($stats['expectedNonZero'] - $stats['expectedNonZeroCountedZero']) / $stats['totalItems']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    </div>
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
</x-layout>
