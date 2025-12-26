<?php
use App\Http\Controllers\Controller;
?>

<x-layout>

    <x-slot:header>
        <x-header>Upload CSV File</x-header>
    </x-slot:header>

    <x-layout-container class="max-w-9xl">

            <form method="post" action="/upload" class="w-4/6 mx-auto" enctype="multipart/form-data">
                @csrf
                <x-form-field fieldName="upload-csv" id="upload-csv" labelText="" type="file" />
                <x-form-submit id="upload-csv">Upload CSV</x-form-submit>
            </form>

        <form method="post" action="/upload-precount" class="w-4/6 mx-auto" enctype="multipart/form-data">
            @csrf
            <x-form-field fieldName="precount-csv" id="precount-csv" labelText="" type="file" />
            <x-form-submit id="upload-precount-csv">Upload Precount CSV</x-form-submit>
        </form>


    </x-layout-container>
</x-layout>

