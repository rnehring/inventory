<?php
use App\Http\Controllers\FunctionController;
?>

<x-layout>
    <x-toast-success id="toast-success"></x-toast-success>
    <x-slot:header>
        <x-header>No Tag Parts</x-header>
    </x-slot:header>

    <x-layout-container class="max-w-9xl">

        <x-search-form formTitle="Add Inventory Without a Tag Number" imageName="/images/notagsidebar.jpg" class="max-w-6xl">

            <form method="post" action="/notag/save" class="w-4/6 mx-auto">
                @csrf
                <x-form-field fieldName="part" labelText="Part Number" />
                <x-form-field fieldName="bin" labelText="Bin" />
                <x-form-field fieldName="count" labelText="Count" />

                <x-form-label>Unit of Measure</x-form-label>
                <select id="uom" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                    <option value="EA">EA</option>
                    <option value="FT">FT</option>
                    <option value="GA">GA</option>
                    <option value="IN">IN</option>
                    <option value="KG">KG</option>
                    <option value="LB">LB</option>
                    <option value="OZ">OZ</option>
                    <option value="PC">PC</option>
                    <option value="QT">QT</option>
                </select>

                <div class="flex items-center ps-4 border border-gray-200 rounded-sm dark:border-gray-700 mt-4">
                    <input checked id="by_weight" type="checkbox" value="" name="bordered-checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-checkbox-2" class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">By Weight?</label>
                </div>

                <x-form-label class="mt-4">Company</x-form-label>
                <select id="company" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected>Company</option>

                    @foreach($companies as $company)
                        <option value="{{ $company['companyCode'] }}">{{ $company['companyName'] }}</option>
                    @endforeach

                </select>

                <x-form-label class="mt-4">Plant</x-form-label>

                <select id="warehouse" name="warehouse" class="block mb-3 py-2.5 px-2 w-full text-sm text-gray-300 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer" required>
                    <option value="">Choose a Warehouse</option>
                    @php

                        foreach($warehouses as $warehouse){
                            echo "<option class='text-gray-800 px-2' value='" . $warehouse->warehouse . "'>" . $warehouse->warehouse . "</option>";
                        }
                    @endphp
                </select>

                <x-form-field fieldName="lot_number" labelText="Lot Number" />
                <x-form-field fieldName="serial_number" labelText="Serial Number" />

                <x-form-submit id="add-notag">Add Part</x-form-submit>
            </form>

        </x-search-form>

        <h5 class="text-xl font-bold text-black mb-2 mt-6 ">All No Tag Parts</h5>
        <hr class="mb-2 bg-gray-950">

        <table id="noTagData" class="mt-8 w-full border-b dark:bg-gray-800 dark:border-gray-700 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <th scope="col" class="px-4 py-3">Part</th>
                <th scope="col" class="px-4 py-3 text-center">Count</th>
                <th scope="col" class="px-4 py-3 text-center">Bin</th>
                <th scope="col" class="px-4 py-3 text-center">UOM</th>
                <th scope="col" class="px-4 py-3 text-center">By Weight?</th>
                <th scope="col" class="px-4 py-3 text-center">Company</th>
                <th scope="col" class="px-4 py-3 text-center">Warehouse</th>
                <th scope="col" class="px-4 py-3 text-center">Lot Number</th>
                <th scope="col" class="px-4 py-3 text-center">Serial Number</th>
                <th scope="col" class="px-4 py-3 text-right">Cost</th>
                <th scope="col" class="px-4 py-3 text-right">Cost Counted</th>
            </thead>
            <tbody class="text-gray-900 px-4 border-b">
            @foreach ($noTagParts as $row)
                <tr class="bg-green-300 border-b">
                    <td class="border-b px-4 py-4"> {{ $row->part}} </td>
                    <td class="border-b px-4 py-4 text-center"> {{ $row->count }} </td>
                    <td class="border-b px-4 py-4 text-center"> {{ $row->bin }} </td>
                    <td class="border-b px-4 py-4 text-center"> {{ $row->uom }} </td>
                    <td class="border-b px-4 py-4 text-center">
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
                    <td class="border-b px-4 py-4 text-center"> {{ FunctionController::epicorCodeToCompanyName($row->company) }} </td>
                    <td class="border-b px-4 py-4 text-center"> {{ $row->warehouse }} </td>
                    <td class="border-b px-4 py-4 text-center"> {{ $row->lot_number }} </td>
                    <td class="border-b px-4 py-4 text-center"> {{ $row->serial_number }} </td>
                    <td class="border-b px-4 py-4 text-right"> ${{ number_format($row->standard_cost, 2, '.', ',') }} </td>
                    <td class="border-b px-4 py-4 text-right"> ${{ number_format($row->cost_counted, 2, '.', ',') }} </td>
                </tr>
            @endforeach
            </tbody>
        </table>



    </x-layout-container>
</x-layout>

