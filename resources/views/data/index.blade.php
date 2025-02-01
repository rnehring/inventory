<x-layout>

    <x-slot:header>
        <x-header>Data</x-header>
    </x-slot:header>

    <x-layout-container>

        <div class="w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 px-2 py-4  my-2">
            <h2 class="text-md font-semibold text-left text-white"> Totals {{ $total }}</h2>
        </div>



        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table id="data" class="w-full border-b dark:bg-gray-800 dark:border-gray-700 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <th scope="col" class="px-2 py-3">Tag</th>
                <th scope="col" class="px-2 py-3">Part</th>
                <th scope="col" class="px-2 py-3">Part Description</th>
                <th scope="col" class="px-2 py-3">Bin</th>
                <th scope="col" class="px-2 py-3">Description</th>
                <th scope="col" class="px-2 py-3">Company</th>
                <th scope="col" class="px-2 py-3">Lot</th>
                <th scope="col" class="px-2 py-3">Serial</th>
                <th scope="col" class="px-2 py-3">Count</th>
                <th scope="col" class="px-2 py-3 text-center">Counted By</th>
                <th scope="col" class="px-2 py-3">UOM</th>
                <th scope="col" class="px-2 py-3 text-center">By Weight</th>
                <th scope="col" class="px-2 py-3" style="text-align:center;">Expected Qty</th>
                <th scope="col" class="px-2 py-3" style="text-align:right;">Cost Ea</th>
                <th scope="col" class="px-2 py-3 text-center">Cost Counted</th>
                <th scope="col" class="px-2 py-3 text-center" >Cost Expected</th>
                <th scope="col" class="px-2 py-3" style="text-align:right;">+/-</th>
            </thead>
            <tbody>

            @foreach($allData as $row)

                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-2 py-4">{{ $row->tag }}</td>
                    <td class="px-2 py-4">{{ $row->part }}</td>
                    <td class="px-2 py-4">{{ $row->part_description }}</td>
                    <td class="px-2 py-4">{{ $row->bin }}</td>
                    <td class="px-2 py-4">{{ $row->description }}</td>
                    <td class="px-2 py-4">{{ $row->company }}</td>
                    <td class="px-2 py-4">{{ $row->lot_number ? $row->lot_number : 'none'}}</td>
                    <td class="px-2 py-4">{{ $row->serial_number ? $row->serial_number : 'none'}}</td>
                    <td class="px-2 py-4">{{ $row->count }}</td>
                    <td class="px-2 py-4">{{ $row->user }}</td>
                    <td class="px-2 py-4">{{ $row->uom }}</td>
                    <td class="px-2 py-4">{{ $row->by_weight }}</td>
                    <td class="px-2 py-4">{{ $row->expected_qty }}</td>
                    <td class="px-2 py-4">{{ $row->standard_cost }}</td>
                    <td class="px-2 py-4 text-right">${{ $row->cost_counted }} </td>
                    <td class="px-2 py-4 text-right">${{ $row->cost_expected }}</td>
                    <td class="px-2 py-4 text-right">${{ $row->plus_minus }}</td>

                </tr>

            @endforeach

            </tbody>
        </table>

            {{ $allData->links() }}
        </div>

    </x-layout-container>
</x-layout>

