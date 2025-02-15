<?php
use App\Http\Controllers\Controller;
?>

<x-layout>

    <x-slot:header>
        <x-header>No Tag Parts</x-header>
    </x-slot:header>

    <x-layout-container class="max-w-9xl">

        <x-search-form formTitle="Add Inventory Without a Tag Number" imageName="/images/notagsidebar.jpg" class="max-w-6xl">

            <form method="post" class="w-4/6 mx-auto">
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
                    <option value="10">PureFlex</option>
                    <option value="20">Nil-Cor</option>
                    <option value="30">Ethyene</option>
                    <option value="40">Hills-McCanna</option>
                    <option value="50">Ramparts Pumps</option>
                    <option value="CC0">Conley Composites</option>
                    <option value="FC0">Flowcor</option>
                    <option value="G50">Endurance Composites</option>
                    <option value="GW0">GWS</option>
                    <option value="PV0">PolyValve</option>
                </select>

                <x-form-label class="mt-4">Plant</x-form-label>

                <select id="warehouse" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-4">
                    <option selected>Choose a Plant</option>
                    <option value="9000-P1">Plant 1</option>
                    <option value="9000-P2">Plant 2</option>
                    <option value="9000-P3">Plant 3</option>
                </select>

                <x-form-field fieldName="lot_number" labelText="Lot Number" />
                <x-form-field fieldName="serial_number" labelText="Serial Number" />

                <x-form-submit id="add-notag">Add Part</x-form-submit>
            </form>

        </x-search-form>

        <table id="partData" class="mt-8 w-full border-b dark:bg-gray-800 dark:border-gray-700 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <th scope="col" class="px-2 py-3">Tag</th>
            <th scope="col" class="px-2 py-3">Part</th>
            <th scope="col" class="px-2 py-3 text-center">UOM</th>
            <th scope="col" class="px-2 py-3 text-center">Count</th>
            <th scope="col" class="px-2 py-3 text-center">By Weight?</th>
            <th scope="col" class="px-2 py-3">Lot Number</th>
            <th scope="col" class="px-2 py-3">Serial Number</th>
            <th scope="col" class="px-2 py-3 text-right">Expected Qty</th>
            <th scope="col" class="px-2 py-3 text-right">Cost</th>
            <th scope="col" class="px-2 py-3 text-right">Cost Counted</th>
            <th scope="col" class="px-2 py-3 text-right">Cost Expected</th>
            <th scope="col" class="px-2 py-3 text-right">+/-</th>
            <th scope="col" class="px-2 py-3 text-center"></th>
            </thead>
            <tbody class="text-gray-900 px-2 border-b">

            </tbody>
        </table>

    </x-layout-container>
</x-layout>

