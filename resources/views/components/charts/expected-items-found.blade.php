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
