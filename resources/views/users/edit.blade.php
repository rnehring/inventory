@php
    use App\Http\Controllers\FunctionController;
@endphp

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('editUser');

        console.log('Form action:', form.action);
        console.log('Form method:', form.method);

        form.addEventListener('submit', function(e) {
            console.log('Form is submitting to:', form.action);
            console.log('Form method:', form.method);
            // Uncomment next line to prevent submission for testing
            // e.preventDefault();
        });
    });
</script>

<x-layout>

    <x-slot:header>
        <x-header>Edit User</x-header>
    </x-slot:header>

    <x-layout-container class="max-w-10xl">

        <div
            class="block max-w-8xl p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 mx-auto">
            <h5 class="text-xl font-bold text-white mb-2 ">
                <button name="usericon" class = 'px-3 py-2.5 text-base font-medium text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-left dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 mr-4 ' >
                    <svg version="1.1" viewBox="0 0 48 48" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="w-8 h-8"><g id="Layer_3_1_"><g><circle cx="23.949" cy="11.708" fill="#241F20" r="11.708"/><path d="M34.165,23.252c-2.724,2.412-6.293,3.889-10.216,3.889c-3.905,0-7.46-1.463-10.179-3.854    C6.679,27.157,1.834,34.921,1.834,43.874c0,1.409,0.127,2.786,0.356,4.126h43.621c0.229-1.34,0.355-2.717,0.355-4.126    C46.166,34.895,41.292,27.109,34.165,23.252z" fill="#241F20"/></g></g></svg>
                </button>

                {{ $user->first_name }}  {{ $user->last_name }}</h5>
            <hr class="mb-2">

            <form id="editUser" name="editUser" class="max-w-10xl mx-auto block w-2/4" action="{{ route('users.update') }}" method="post" >
                @csrf
                <input type="hidden" name="id" id="id" value="{{ $user->id }}" />
                <x-form-field fieldName="first_name" labelText="First Name" value="{{ $user->first_name }}"></x-form-field>
                <x-form-field fieldName="last_name" labelText="Last Name" value="{{ $user->last_name }}"></x-form-field>
                <x-form-field fieldName="initials" labelText="Initials" value="{{ $user->initials }}"></x-form-field>
                <x-form-field fieldName="email" labelText="Email Address" value="{{ $user->email }}"></x-form-field>

                <label for="password"
                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    required>

                <label for="company"
                       class="mt-6 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Company</label>
                <select
                    id="company"
                    name="company"
                    class="mt-4 block mb-3 py-2.5 px-0 w-full text-sm text-gray-300 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                    <option value="00" selected>Choose Your Company</option>
                    <option value="10" @if( $user->company  == "10" ) selected @endif>PureFlex</option>
                    <option value="20" @if(  $user->company  == "20" ) selected @endif>Nilcor</option>
                    <option value="30" @if(  $user->company  == "30" ) selected @endif>Ethylene</option>
                    <option value="40" @if(  $user->company  == "40" ) selected @endif>Hills-McCanna</option>
                    <option value="CC0" @if(  $user->company  == "CC0" ) selected @endif>Conley Composites</option>
                    <option value="PV0" @if(  $user->company  == "PV0" ) selected @endif>PolyValve</option>
                    <option value="50" @if(  $user->company  == "50" ) selected @endif>Ramparts Pumps</option>
                    <option value="G50" @if(  $user->company  == "G50" ) selected @endif>Endurance Composites</option>
                </select>

                <label for="user_type"
                       class='block mb-2 text-sm font-medium text-gray-900 dark:text-white'>Password</label>
                <select
                    id="user_type"
                    name="user_type"
                    class="mt04 block mb-3 py-2.5 px-0 w-full text-sm text-gray-300 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                    <option value="1" @if( $user->user_type == "1") selected @endif>User</option>
                    <option value="2" @if( $user->user_type == "2") selected @endif>Admin</option>
                </select>

                <x-new-user-button id="updateUser" name="updateUser">Update User</x-new-user-button>

            </form>
        </div>


    </x-layout-container>
</x-layout>

