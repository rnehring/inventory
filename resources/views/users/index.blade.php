@php
    use App\Http\Controllers\FunctionController;
@endphp

<x-layout>

    <x-slot:header>
        <x-header>Users</x-header>
    </x-slot:header>

    <x-layout-container class="max-w-10xl">

        <div
            class="block max-w-8xl p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 mx-auto">
            <h5 class="text-xl font-bold text-white mb-2 ">User Admin</h5>
            <hr class="mb-2">

            <table id="users"
                   class="w-full border-b dark:bg-gray-800 dark:border-gray-700 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <th scope="col" class="px-2 py-3 text-center"> ID</th>
                    <th scope="col" class="px-2 py-3">First Name</th>
                    <th scope="col" class="px-2 py-3">Last Name</th>
                    <th scope="col" class="px-2 py-3 text-center">Initials</th>
                    <th scope="col" class="px-2 py-3">Email Address</th>
                    <th scope="col" class="px-2 py-3 text-center">Company</th>
                    <th scope="col" class="px-2 py-3 text-center">User Type</th>
                    <th scope="col" class="px-2 py-3 text-center">Edit</th>
                    <th scope="col" class="px-2 py-3 text-center">Delete</th>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                            <td class="text-center">{{ $user->id }}</td>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td class="text-center">{{ $user->initials }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">{{ FunctionController::epicorCodeToCompanyName($user->company) }}</td>
                            <td class="text-center" >{{ $user->user_type == 1 ? "User" : "Admin" }}</td>
                            <td class="text-center py-2 px-2">
                                <a href="/users/edit/{{ $user->id }}">
                                    <x-user-edit-button></x-user-edit-button>
                                </a>
                            </td>
                            <td class="text-center py-2 px-2">
                                <x-user-delete-button userId="{{ $user->id }}"></x-user-delete-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


            <a href="/users/new">
                <x-new-user-button id="new_user" name="new_user">New User</x-new-user-button>
            </a>
        </div>

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
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this user?</h3>
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

