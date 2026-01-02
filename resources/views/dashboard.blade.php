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

            <div class="mt-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-xl font-bold text-gray-900 dark:text-white">
                            <x-ri-numbers-fill class="w-6 h-6 inline-block mr-2 text-blue-500"/>
                            Count Accuracy Breakdown
                        </h5>
                    </div>

                    <div class="flex p-2 flex-row">
                        @include('components.charts.expected-items-not-found')
                        @include('components.charts.expected-items-found')
                    </div>
                </div>
            </div>

            @include('components.charts.warehouse-comparison')

</x-layout>
