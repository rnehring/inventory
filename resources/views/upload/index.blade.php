<?php
use App\Http\Controllers\Controller;
?>

<x-layout>

    <x-slot:header>
        <x-header>Upload CSV File</x-header>
    </x-slot:header>

    <x-layout-container class="max-w-9xl">

            <form method="post" action="/upload" class="w-full mx-auto" enctype="multipart/form-data" id="inventory-upload-form">
                @csrf

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <div class="flex items-center justify-center w-10/12 m-4 align-center mx-auto">
                    <label for="upload-inventory-csv" id="drop-zone" class="flex flex-col items-center justify-center w-full h-64 bg-gray-800 border-2 border-dashed border-gray-600 rounded-lg cursor-pointer hover:bg-gray-700 hover:border-gray-500 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6" id="drop-zone-content">
                            <svg class="w-8 h-8 mb-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h3a3 3 0 0 0 0-6h-.025a5.56 5.56 0 0 0 .025-.5A5.5 5.5 0 0 0 7.207 9.021C7.137 9.017 7.071 9 7 9a4 4 0 1 0 0 8h2.167M12 19v-9m0 0-2 2m2-2 2 2"/></svg>
                            <p class="mb-2 text-sm text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-400">CSV files only</p>
                        </div>
                        <input id="upload-inventory-csv" name="upload-inventory-csv" type="file" accept=".csv" class="hidden" />
                    </label>
                </div>

                <div id="file-info" class="hidden mt-4 p-4 bg-gray-700 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-sm text-gray-300" id="file-name"></span>
                        </div>
                        <button type="button" id="remove-file" class="text-red-400 hover:text-red-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>

                <x-upload-button>Upload Inventory CSV</x-upload-button>
{{--                <x-form-submit class="mx-auto w-6 block" id="upload-csv">Upload Inventory CSV</x-form-submit>--}}
            </form>

{{--        <form method="post" action="/upload-precount" class="w-4/6 mx-auto" enctype="multipart/form-data">--}}
{{--            @csrf--}}
{{--            <x-form-field fieldName="precount-csv" id="precount-csv" labelText="" type="file" />--}}
{{--            <x-form-submit id="upload-precount-csv">Upload Precount CSV</x-form-submit>--}}
{{--        </form>--}}


    </x-layout-container>
</x-layout>

