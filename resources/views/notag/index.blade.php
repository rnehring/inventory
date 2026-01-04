<?php
use App\Http\Controllers\FunctionController;
?>

<script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.9/dist/autoComplete.min.js"></script>
<script>

    const userType = {{ Auth::user()->user_type ?? 0 }};
    const acBins = @php echo $bins; @endphp;
    const acParts = @php echo $parts; @endphp;
</script>

<x-layout>
    <x-toast-success id="toast-success"></x-toast-success>
    <x-toast-error id="toast-error"></x-toast-error>
    <x-slot:header>
        <x-header>No Tag Parts</x-header>
    </x-slot:header>

    <x-layout-container class="max-w-9xl">


        <x-search-form
            formTitle="Add Inventory Without a Tag Number"
            imageName="/images/notagsidebar.jpg"
            imageHeight="760"
            titleTopMargin="mt-8"
            class="max-w-6xl self-start mt-8 clear-both"
        >

            <form method="post" action="/notag/save" class="w-4/6 mx-auto" name="NoTagForm" id="NoTagForm">
                @csrf
                <x-form-field id="part" fieldName="part" labelText="Part Number" />

                <x-form-field id="bins" fieldName="bins" labelText="Bin" />

                <x-form-field fieldName="count" labelText="Count" />

                <x-form-label>Unit of Measure</x-form-label>
                <select id="uom" name="uom" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled>
                    @foreach($uoms as $uom)
                        <option value="{{ $uom }}">{{ $uom }}</option>
                    @endforeach
                </select>

                <div class="flex items-center ps-4 border border-gray-200 rounded-sm dark:border-gray-700 mt-4">
                    <input id="by_weight" name="by_weight" type="checkbox" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-checkbox-2" class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">By Weight?</label>
                </div>

                <x-form-field fieldName="lot_number" labelText="Lot Number" />
                <x-form-field fieldName="serial_number" labelText="Serial Number" />
                <input type="hidden" id="warehouse" name="warehouse" value="{{ session('plant') }}" />
                <x-form-submit id="add-notag">Add Part</x-form-submit>
            </form>

        </x-search-form>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg py-4 px-6 flex justify-between mt-6 items-center">

            <h5 class="text-xl font-bold dark:text-gray-200">All No Tag Parts</h5>
            <div>
                <a href='/notag/edit-all'>
                    <button class='text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 text'>Edit All No Tag Parts</button>
                </a>
            </div>

        </div>
        <table id="noTagData" class="mt-4 w-full border-b dark:bg-gray-800 dark:border-gray-700 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <th scope="col" class="px-4 py-3">Tag</th>
                <th scope="col" class="px-4 py-3">Part</th>
                <th scope="col" class="px-4 py-3 text-center">Count</th>
                <th scope="col" class="px-4 py-3 text-center">Bin</th>
                <th scope="col" class="px-4 py-3 text-center">UOM</th>
                <th scope="col" class="px-4 py-3 text-center">By Weight?</th>
                <th scope="col" class="px-4 py-3 text-center">Warehouse</th>
                <th scope="col" class="px-4 py-3 text-center">Lot Number</th>
                <th scope="col" class="px-4 py-3 text-center">Serial Number</th>

                @if( Auth::user()->user_type->canManageUsers() )
                    <th scope="col" class="px-4 py-3 text-right">Cost</th>
                    <th scope="col" class="px-4 py-3 text-right">Cost Counted</th>
                @endif
                <th scope="col" class="px-4 py-3 text-center">Edit</th>
            </thead>
            <tbody class="text-gray-900 px-4 border-b">
            @foreach ($noTagParts as $row)
                @if($row->possible_dupe == '1')
                    <tr class="bg-green-300 bg-red-600 border-b">
                @else
                    <tr class="bg-green-300 border-b">
                @endif
                    <td class="border-b px-4 py-4"> {{ $row->tag}} </td>
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
                    <td class="border-b px-4 py-4 text-center"> {{ $row->warehouse }} </td>
                    <td class="border-b px-4 py-4 text-center"> {{ $row->lot_number }} </td>
                    <td class="border-b px-4 py-4 text-center"> {{ $row->serial_number }} </td>
                    @if( Auth::user()->user_type->canManageUsers() )
                        <td class="border-b px-4 py-4 text-right"> ${{ number_format($row->standard_cost, 2, '.', ',') }} </td>
                        <td class="border-b px-4 py-4 text-right"> ${{ number_format($row->cost_counted, 2, '.', ',') }} </td>
                    @endif
                    <td class="text-center py-2 px-2">
                        <a href="/notag/edit/{{ $row->id }}">
                            <x-notag-edit-button></x-notag-edit-button>
                        </a>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>



    </x-layout-container>
</x-layout>

