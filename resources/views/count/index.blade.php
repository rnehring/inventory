<?php
   use App\Http\Controllers\Controller;
?>

<x-layout>

    <x-slot:header>
        <x-header>Inventory Count</x-header>
    </x-slot:header>

        <x-search-form formTitle="Search By Part/Bin" imageName="/images/bucket-expansion-joints.jpg">

            <form method="post" class="w-4/6 mx-auto">
                @csrf
                <x-form-field fieldName="part" labelText="Part" />
                <x-form-field fieldName="bin" labelText="Bin" />
                <x-form-submit id="get-part">Search</x-form-submit>
            </form>

        </x-search-form>

</x-layout>

