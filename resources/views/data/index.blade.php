@php
    use App\Http\Controllers\FunctionController;
@endphp

<x-layout>

    <x-slot:header>
        <x-header>Data</x-header>
    </x-slot:header>

    <x-layout-container class="max-w-10xl">

        <div
            class="block max-w-10xl p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 ">
            <h5 class="text-xl font-bold text-white mb-2 ">Export Data</h5>
            <hr class="mb-2">
            <p class="mb-2 font-bold text-white">Choose all, any, or multiple companies below to export data for.</p>

            <form class="max-w-10xl mx-auto" method="post" action="/download-data">
                @csrf
                <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white mt-8">

                    @php
                    $companies = ['10', '20', '30', '40', '50', 'CC0', 'G50', 'PV0'];
                    foreach ($companies as $company) {
                        echo '<li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">';
                        echo '<div class="flex items-center ps-3">';
                        echo '<input id="companies" name="companies[]" type="checkbox" value="' . $company . '" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">';
                        echo '<label for="companies" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">' . FunctionController::epicorCodeToCompanyName($company) . '</label>';
                        echo '</div>';
                        echo '</li>';
                    }
                    @endphp
                </ul>
                <x-csv-button id="download_csv" name="download_csv">Download CSV</x-csv-button>
            </form>
        </div>

        <div
            class="block max-w-10xl p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 mt-8">
            <h5 class="text-xl font-bold text-white mb-2 ">Current Data</h5>
            <hr class="mb-2">
            <p class="mb-2 font-bold text-white">Choose all, any, or multiple companies below to view data for.</p>
            <form class="max-w-10xl mx-auto" method="post" action="/company-data">
                @csrf
                <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white mt-8">
                    @php
                    foreach ($companies as $company) {
                        echo '<li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">';
                        echo '<div class="flex items-center ps-3">';
                        echo '<input id="companies" name="companies[]" type="checkbox" value="' . $company . '" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">';
                        echo '<label for="companies" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">' . FunctionController::epicorCodeToCompanyName($company) . '</label>';
                        echo '</div>';
                        echo '</li>';
                    }
                    @endphp
                </ul>
                <x-csv-button name="update-data" id="update-data">Update Data</x-csv-button>
            </form>
        </div>

        <div class="w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 px-2 py-4 my-2 flex items-center justify-between">
            <h5 class="text-xl font-bold text-white ml-4">Inventory Totals</h5>
            <div>
                <div
                    class="inline-block focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900">
                    Inventory Total : {{ FunctionController::formatCurrency($total) }}
                </div>
                <div
                    class="inline-block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    No Tag Total : {{ FunctionController::formatCurrency($noTagTotal) }}
                </div>
                <div
                    class="inline-block focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                    Total Plus/Minus : {{ FunctionController::formatCurrency($totalPlusMinus) }}
                </div>
            </div>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table id="data"
                   class="w-full border-b dark:bg-gray-800 dark:border-gray-700 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <th scope="col" class="px-2 py-3 text-center"> Counted</th>
                    <th scope="col" class="px-2 py-3"> Tag</th>
                    <th scope="col" class="px-2 py-3"> Part</th>
                    <th scope="col" class="px-2 py-3"> Part Description</th>
                    <th scope="col" class="px-2 py-3"> Bin</th>
                    <th scope="col" class="px-2 py-3"> Description</th>
                    <th scope="col" class="px-2 py-3"> Company</th>
                    <th scope="col" class="px-2 py-3"> Count</th>
                    <th scope="col" class="px-2 py-3 text-center"> Counted By</th>
                    <th scope="col" class="px-2 py-3"> UOM</th>
                    <th scope="col" class="px-2 py-3 text-center"> By Weight</th>
                    <th scope="col" class="px-2 py-3" style="text-align:center;"> Expected Qty</th>
                    <th scope="col" class="px-2 py-3" style="text-align:right;"> Cost Ea</th>
                    <th scope="col" class="px-2 py-3 text-right"> Cost Counted</th>
                    <th scope="col" class="px-2 py-3 text-right"> Cost Expected</th>
                    <th scope="col" class="px-2 py-3 text-center"> +/-</th>
                </thead>
                <tbody>
                @foreach ($allData as $row)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-2 py-4">
                            @if ($row->counted == '1')
                                @php
                                    echo "<span class='flex w-3 h-3 bg-green-500 rounded-full mx-auto content-center'></span>";
                                @endphp
                            @endif
                        </td>
                        <td class="px-2 py-4"> {{ $row->tag}} </td>
                        <td class="px-2 py-4"> {{ $row->part}} </td>
                        <td class="px-2 py-4"> {{ Str::limit($row->part_description, 50) }} </td>
                        <td class="px-2 py-4"> {{ $row->bin }} </td>
                        <td class="px-2 py-4"> {{ $row->description }} </td>
                        <td class="px-2 py-4"> {{ FunctionController::epicorCodeToCompanyName($row->company) }} </td>
                        <td class="px-2 py-4 text-center"> {{ $row->count }} </td>
                        <td class="px-2 py-4"> {{ $row->user }} </td>
                        <td class="px-2 py-4"> {{ $row->uom }} </td>
                        <td class="px-2 py-4 text-center">
                            @if ($row->by_weight == '1')
                                @php
                                    echo "Yes";
                                @endphp
                            @else
                                @php
                                    echo "No";
                                @endphp
                            @endif
                        </td>
                        <td class="px-2 py-4 text-center"> {{ Number::format($row->expected_qty, precision: 2) }} </td>
                        <td class="px-2 py-4 text-right"> ${{ number_format($row->standard_cost, 2, '.', ',') }} </td>
                        <td class="px-2 py-4 text-right"> ${{ number_format($row->cost_counted, 2, '.', ',') }} </td>
                        <td class="px-2 py-4 text-right"> ${{ number_format($row->cost_expected, 2, '.', ',') }} </td>
                        <td class="px-2 py-4 text-right"> ${{ number_format($row->plus_minus, 2, '.', ',') }} </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $allData->links() }}
        </div>

    </x-layout-container>
</x-layout>

