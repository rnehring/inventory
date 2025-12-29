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
    <x-slot:header>
        <x-header>No Tag Parts</x-header>
    </x-slot:header>

    <x-layout-container class="max-w-9xl">
        @if( Auth::user()->user_type->canManageUsers() )
        <div class="max-w-4xl mb-8 dark:bg-gray-800 dark:border-gray-700 border border-gray-200 rounded-lg shadow float-right clear-both">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg p-4 text-white">
                <div class="flex justify-end gap-3">
                    <div class="flex items-center justify-between mr-4">
                        <h5 class="text-xl font-bold text-gray-900 dark:text-white">
                            <x-ri-remix-fill class="w-6 h-6 inline-block mr-2 text-blue-500"/>
                            Quick Links
                        </h5>
                    </div>

                    <a href="{{ route('data.export-notag') }}"
                       class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg text-sm font-medium transition-all duration-200 border border-white/30">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Export No-Tag Data
                    </a>
                    <a href="{{ route('data.export-inventory') }}"
                       class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg text-sm font-medium transition-all duration-200 border border-white/30">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Inventory
                    </a>
                </div>
            </div>
        </div>
        @endif

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

                <x-form-label hidden>Unit of Measure</x-form-label>
                <select id="uom" name="uom" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" hidden>

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
                    <input id="by_weight" name="by_weight" type="checkbox" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-checkbox-2" class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">By Weight?</label>
                </div>

                <x-form-field fieldName="lot_number" labelText="Lot Number" />
                <x-form-field fieldName="serial_number" labelText="Serial Number" />
                <input type="hidden" id="warehouse" name="warehouse" value="{{ session('plant') }}" />
                <x-form-submit id="add-notag">Add Part</x-form-submit>
            </form>

        </x-search-form>

        <h5 class="text-xl font-bold text-black mb-2 mt-6 ">All No Tag Parts</h5>

        <table id="noTagData" class="mt-8 w-full border-b dark:bg-gray-800 dark:border-gray-700 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
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
                <tr class="bg-green-300 border-b">
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

