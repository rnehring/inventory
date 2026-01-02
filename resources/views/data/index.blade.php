@php
    use App\Http\Controllers\FunctionController;
@endphp

<x-layout>

    <x-slot:header>
        <x-header>Data</x-header>
    </x-slot:header>

    <x-layout-container class="max-w-10xl">

        <div class="w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 px-2 py-4 my-4 flex items-center justify-between">
            <h5 class="text-xl font-bold text-white ml-4">Inventory Totals</h5>
            <div>
                <div
                    class="inline-block focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-bold rounded-sm text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900">
                    Inventory Total : {{ FunctionController::formatCurrency($total) }}
                </div>
                <div
                    class="inline-block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-bold rounded-sm text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    No Tag Total : {{ FunctionController::formatCurrency($noTagTotal) }}
                </div>
                <div
                    class="inline-block focus:outline-none text-white bg-violet-700 hover:bg-violet-800 focus:ring-4 focus:ring-violet-300 font-bold rounded-sm text-sm px-5 py-2.5 me-2 mb-2 dark:bg-violet-600 dark:hover:bg-violet-700 dark:focus:ring-violet-900">
                    Total Plus/Minus (Tagged Inventory) : {{ FunctionController::formatCurrency($totalPlusMinus) }}
                </div>
                <div
                    class="inline-block focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-bold rounded-sm text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                    Total Plus/Minus (With No Tag): {{ FunctionController::formatCurrency($totalPlusMinus + $noTagTotal) }}
                </div>
            </div>
        </div>

        <div id="data-container" class="w-full border-b dark:bg-gray-800 dark:border-gray-700 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded-lg shadow flex flex-col">

            <div
                class="w-full px-4 bg-white rounded-lg shadow-sm dark:bg-gray-800 mt-4">
                <h5 class="text-xl font-bold text-white mb-2 "><x-ri-database-line class="w-6 h-6 inline-block mr-2"/>Current Data</h5>
                <hr class="mb-4 border-gray-600">
            </div>

            <p class="font-bold text-white align-center text-center">Filter Data Using The Options Below</p>

            <div class="flex flex-row flex-nowrap gap-2 w-full items-center dark:cb-filters px-4 py-3 border bg-gray-600/25 border-gray-700 mt-2 mb-0" id="filters">

            </div>

            <div id="grid" class="w-full border-b dark:bg-gray-800 dark:border-gray-700 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 p-4 pt-2"></div>


        </div>

    </x-layout-container>
</x-layout>

