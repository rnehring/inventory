<header class="bg-white shadow mx-auto px-4 py-4 sm:px-6 lg:px-8">
    <div class="flex justify-between max-w-9xl mx-auto">
    <div class="w-full dark:bg-gray-800 dark:border-gray-700 border border-gray-200 rounded-lg shadow">
        <div class="dark:bg-gray-800 rounded-lg shadow-lg p-4 text-white">
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
        </div>
    </div>
    </div>
</header>
