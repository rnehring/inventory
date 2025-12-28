<?php
use App\Http\Controllers\Controller;
?>

<x-layout>

    <x-slot:header>
        <x-header>Upload CSV File</x-header>
    </x-slot:header>

    <x-layout-container class="max-w-9xl">

            <form method="post" action="/upload" class="w-full mx-auto" enctype="multipart/form-data">
                @csrf


                <div class="flex items-center justify-center w-full">
                    <label for="upload-inventory-csv" class="flex flex-col items-center justify-center w-full h-64 bg-gray-800 border border-dashed border-default-strong rounded-base cursor-pointer hover:bg-gray-900 text-slate-200">
                        <div class="flex flex-col items-center justify-center text-body pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h3a3 3 0 0 0 0-6h-.025a5.56 5.56 0 0 0 .025-.5A5.5 5.5 0 0 0 7.207 9.021C7.137 9.017 7.071 9 7 9a4 4 0 1 0 0 8h2.167M12 19v-9m0 0-2 2m2-2 2 2"/></svg>
                            <p class="mb-2 text-sm"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs">CSV</p>
                        </div>
                        <input id="upload-inventory-csv" type="file" class="hidden" />
                    </label>
                </div>


                <x-form-submit id="upload-csv">Upload CSV</x-form-submit>
            </form>

        <form method="post" action="/upload-precount" class="w-4/6 mx-auto" enctype="multipart/form-data">
            @csrf
            <x-form-field fieldName="precount-csv" id="precount-csv" labelText="" type="file" />
            <x-form-submit id="upload-precount-csv">Upload Precount CSV</x-form-submit>
        </form>


    </x-layout-container>
</x-layout>

