<?php
use App\Http\Controllers\Controller;
?>

<x-layout>

    <x-slot:header>
        <x-header>Inventory Pre-Count</x-header>
    </x-slot:header>

    <x-layout-container class="max-w-9xl">

        <x-search-form formTitle="Search By Part/Bin" imageName="/images/precount.jpg" class="max-w-4xl">

            <form method="post" class="w-4/6 mx-auto">
                @csrf
                <x-form-field fieldName="part" labelText="Part" />
                <x-form-field fieldName="bin" labelText="Bin" />
                <x-form-submit id="get-part">Search</x-form-submit>
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

