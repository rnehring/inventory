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
                                <form action="/users/edit" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $user->id }}" />
                                    <x-user-edit-button></x-user-edit-button>
                                </form>
                            </td>
                            <td class="text-center py-2 px-2">
                                <form action="/users/delete" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $user->id }}" />
                                    <x-user-delete-button></x-user-delete-button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


            <form class="max-w-10xl mx-auto" method="post" action="/users/new">
                @csrf
                <input type="hidden" value="{{ $user->id }}" />
                <x-new-user-button id="new_user" name="new_user">New User</x-new-user-button>
            </form>
        </div>


    </x-layout-container>
</x-layout>

