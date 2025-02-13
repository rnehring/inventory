<?php
   use App\Http\Controllers\Controller;
?>

<x-layout>

    <x-slot:header>
        <x-header>Inventory Count</x-header>
    </x-slot:header>

    <x-layout-container class="max-w-9xl">

        <x-search-form formTitle="Search By Part/Bin" imageName="/images/bucket-expansion-joints.jpg">

            <form method="post" class="w-4/6 mx-auto">
                @csrf
                <x-form-field fieldName="part" labelText="Part" />
                <x-form-field fieldName="bin" labelText="Bin" />
                <x-form-submit id="get-part">Search</x-form-submit>
            </form>

        </x-search-form>

        <table id="partData" class="mt-6 w-full border-b dark:bg-gray-800 dark:border-gray-700 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <th scope="col" class="px-2 py-3">Tag</th>
            <th scope="col" class="px-2 py-3">Part</th>
            <th scope="col" class="px-2 py-3">UOM</th>
            <th scope="col" class="px-2 py-3">Count</th>
            <th scope="col" class="px-2 py-3">By Weight?</th>
            <th scope="col" class="px-2 py-3">Lot Number</th>
            <th scope="col" class="px-2 py-3">Serial Number</th>
            <th scope="col" class="px-2 py-3">Expected Qty</th>
            <th scope="col" class="px-2 py-3">Cost</th>
            <th scope="col" class="px-2 py-3 text-center">Cost Counted</th>
            <th scope="col" class="px-2 py-3">Cost Expected</th>
            <th scope="col" class="px-2 py-3 text-center">+/-</th>
            <th scope="col" class="px-2 py-3 text-center"></th>
            </thead>
            <tbody class="text-gray-900 px-2 border-b">

            </tbody>
        </table>

    </x-layout-container>
</x-layout>

