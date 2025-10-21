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
                <x-form-field fieldName="csvfile" id="csvfile" labelText="Upload CSV File" type="file" />
                <x-form-submit id="upload-csv">Upload CSV</x-form-submit>
            </form>




    </x-layout-container>
</x-layout>

