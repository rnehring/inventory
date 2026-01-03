<?php
use App\Http\Controllers\FunctionController;
?>
<script>
    const userType = {{ Auth::user()->user_type ?? 0 }};
</script>

<x-layout>
    <x-toast-success id="toast-success"></x-toast-success>
    <x-toast-error id="toast-error"></x-toast-error>
    <x-slot:header>
        <x-header>All No Tag Parts</x-header>
    </x-slot:header>

    <x-layout-container class="max-w-9xl">

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
                <th scope="col" class="px-4 py-3 text-center">Delete</th>
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
                    <td class="text-center py-2 px-2">
                        <x-notag-delete-button noTagId="{{ $row->id }}"></x-notag-delete-button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div id="deleteModal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="deleteModal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-4 md:p-5 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this No Tag Part?</h3>
                        <button id="yesDelete" data-modal-hide="deleteModal" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                            Yes, I'm sure
                        </button>
                        <button id="noCancel" data-modal-hide="deleteModal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No, cancel</button>
                    </div>
                </div>
            </div>
        </div>

    </x-layout-container>
</x-layout>



