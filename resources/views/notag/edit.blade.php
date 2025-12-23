<?php
use App\Http\Controllers\FunctionController;
?>

<x-layout>
    <x-toast-success id="toast-success"></x-toast-success>
    <x-slot:header>
        <x-header>No Tag Parts</x-header>
    </x-slot:header>

    <x-layout-container class="max-w-9xl">

        <x-search-form formTitle="Update No Tag Part" imageName="/images/notagsidebar.jpg" class="max-w-6xl">

            <form method="post" action="{{ route('notag.update') }}" class="w-4/6 mx-auto" name="NoTagForm" id="NoTagForm">
                @csrf
                <input type="hidden" name="id" value="{{ $noTagPart->id }}" />
                <x-form-field fieldName="tag" labelText="Tag" value="{{ $noTagPart->tag }}"/>
                <x-form-field fieldName="part" labelText="Part Number" value="{{ $noTagPart->part }}"/>
                <x-form-field fieldName="bin" labelText="Bin" value="{{ $noTagPart->bin }}"/>
                <x-form-field fieldName="count" labelText="Count" value="{{ $noTagPart->count }}"/>

                <x-form-label>Unit of Measure</x-form-label>
                <select id="uom" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                    <option value="EA" @if( $noTagPart->uom  == "EA" ) selected @endif>EA</option>
                    <option value="FT" @if( $noTagPart->uom  == "FT" ) selected @endif>FT</option>
                    <option value="GA" @if( $noTagPart->uom  == "GA" ) selected @endif>GA</option>
                    <option value="IN" @if( $noTagPart->uom  == "IN" ) selected @endif>IN</option>
                    <option value="KG" @if( $noTagPart->uom  == "KG" ) selected @endif>KG</option>
                    <option value="LB" @if( $noTagPart->uom  == "LB" ) selected @endif>LB</option>
                    <option value="OZ" @if( $noTagPart->uom  == "OZ" ) selected @endif>OZ</option>
                    <option value="PC" @if( $noTagPart->uom  == "PC" ) selected @endif>PC</option>
                    <option value="QT" @if( $noTagPart->uom  == "QT" ) selected @endif>QT</option>
                </select>

                <div class="flex items-center ps-4 border border-gray-200 rounded-sm dark:border-gray-700 mt-4">
                    <input id="by_weight" type="checkbox" value="" name="bordered-checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" @if( $noTagPart->by_weight  == 1 ) checked @endif>
                    <label for="bordered-checkbox-2" class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">By Weight?</label>
                </div>

                <x-form-label class="mt-4">Plant</x-form-label>

                <select id="warehouse" name="warehouse" class="block mb-3 py-2.5 px-2 w-full text-sm text-gray-300 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer" required>
                    <option value="">Choose a Warehouse</option>
                    @php
                        foreach($warehouses as $warehouse){
                            echo "<option class='text-gray-800 px-2' value='" . $warehouse->warehouse . "' @if( $noTagPart->warehouse == $warehouse->warehouse) selected @endif>" . $warehouse->warehouse . "</option>";
                        }
                    @endphp
                </select>

                <x-form-field fieldName="lot_number" labelText="Lot Number" value="{{ $noTagPart->lot_number }}"/>
                <x-form-field fieldName="serial_number" labelText="Serial Number" value="{{ $noTagPart->serial_number }}" />

                <x-form-submit id="update-notag">Update Part</x-form-submit>
            </form>

        </x-search-form>





    </x-layout-container>
</x-layout>

