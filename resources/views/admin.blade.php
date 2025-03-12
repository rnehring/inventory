<x-layout>

    <x-slot:header>
        <x-header></x-header>
    </x-slot:header>



    <div class="max-w-7xl p-16 text-center bg-white border border-gray-200 rounded-lg shadow-sm  dark:bg-gray-800 dark:border-gray-700 m-auto mt-20 shadow-md">

        <img class="max-w-sm items-center m-auto" id="company-logo" src="{{URL::asset('/images/company-logos/logo-white.png')}}" alt="company-logo">

        <h5 class="mb-4 text-3xl font-bold text-gray-900 dark:text-white mt-6">Admin</h5>

        <div class="items-center justify-center space-y-4 sm:flex sm:space-y-0 sm:space-x-4 rtl:space-x-reverse">

            <form method="post" action="/create-manager" class="w-4/6 mx-auto">
                @csrf


                <x-form-field fieldName="email" type="email" labelText="Email" required />
                <x-form-field fieldName="password" type="password" labelText="Password" required />
                <x-form-submit id="login">Create</x-form-submit>
            </form>

        </div>
    </div>


</x-layout>

