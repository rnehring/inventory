<x-layout>
    <x-slot:header>
        <x-header>Dashboard</x-header>
    </x-slot:header>

    <div class="py-6">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">

            {{-- Overall Health Score Card --}}
            <div class="max-w-10xl mb-8 dark:bg-gray-800 dark:border-gray-700 border border-gray-200 rounded-lg shadow">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                    {{-- Quick Links Row --}}
                    <div class="flex justify-end gap-3">
                        <div class="flex items-center justify-between mr-4">
                            <h5 class="text-xl font-bold text-gray-900 dark:text-white">
                                <x-ri-remix-fill class="w-6 h-6 inline-block mr-2 text-blue-500"/>
                                Quick Links
                            </h5>
                        </div>
                        <a href="{{ route('data.export-inventory') }}"
                           class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg text-sm font-medium transition-all duration-200 border border-white/30">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export Inventory
                        </a>
                        <a href="{{ route('data.export-notag') }}"
                           class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg text-sm font-medium transition-all duration-200 border border-white/30">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Export No-Tag Data
                        </a>
                        <a href="{{ route('users.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg text-sm font-medium transition-all duration-200 border border-white/30">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            User Management
                        </a>
                    </div>

                    {{-- Main Score Display --}}
{{--                    <div class="flex items-center justify-between">--}}
{{--                        <div>--}}
{{--                            <p class="text-sm opacity-90 mb-2">Overall Expected Reliability</p>--}}
{{--                            <div class="flex items-baseline gap-4">--}}
{{--                                <h1 class="text-6xl font-bold">{{ number_format($reliabilityScore, 1) }}%</h1>--}}
{{--                                <span class="text-2xl opacity-75">/ 100</span>--}}
{{--                            </div>--}}
{{--                            <p class="text-sm mt-2 opacity-90">--}}
{{--                                {{ number_format($stats['expectedZeroCountedZero'] + ($stats['expectedNonZero'] - $stats['expectedNonZeroCountedZero'])) }}--}}
{{--                                of {{ number_format($stats['totalItems']) }} items matched expectations--}}
{{--                            </p>--}}
{{--                        </div>--}}
{{--                        <div class="text-right">--}}
{{--                            <div class="text-7xl mb-2"><img class="max-w-80 max-h-48" src="{{URL::asset('/images/data.jpg')}}" alt="analytics"></div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>


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
                </div>
            </div>

            {{-- Warehouse Details Table --}}
            <div class="mt-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
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
                                colors: '#6B7280'
                            }
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Number of Items'
                        },
                        labels: {
                            style: {
                                colors: '#6B7280'
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        offsetY: 0,
                        labels: {
                            colors: '#6B7280'
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val + " items"
                            }
                        }
                    },
                    theme: {
                        mode: 'dark'
                    }
                };

                var warehouseChart = new ApexCharts(document.querySelector("#warehouseChart"), warehouseOptions);
                warehouseChart.render();
            });
        </script>
    @endpush
</x-layout>
