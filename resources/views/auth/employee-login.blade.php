<x-layout>

    <x-slot:header>
        <x-header></x-header>
    </x-slot:header>



    <div class="max-w-7xl p-16 text-center bg-white border border-gray-200 rounded-lg shadow-sm  dark:bg-gray-800 dark:border-gray-700 m-auto mt-20 shadow-md">

        <img class="max-w-sm items-center m-auto" id="company-logo" src="{{URL::asset('/images/company-logos/logo-white.png')}}" alt="company-logo">

        <h5 class="mb-4 text-3xl font-bold text-gray-900 dark:text-white mt-6">Employee Login</h5>
        <p class="mb-8 text-base text-gray-500 sm:text-lg dark:text-gray-400">Choose the company you work for from the dropdown menu, and then enter your initials. Please use all 3 of your initials, including middle name.  If you don't have a middle name, use 'x' as the middle initial.</p>

        <div class="items-center justify-center space-y-4 sm:flex sm:space-y-0 sm:space-x-4 rtl:space-x-reverse">

            <form method="post" action="/employee-login" id="loginForm" class="w-4/6 mx-auto">
                @csrf

                <select id="company" class="block mb-3 py-2.5 px-0 w-full text-sm text-gray-300 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                    <option value="logo-white.png" selected data-cc="00">Choose Your Company</option>
                    <option value="pureflex-logo-grey-2.png" data-cc="10">PureFlex</option>
                    <option value="nil-cor-logo.png" data-cc="20">Nilcor</option>
                    <option value="ethylene-new.png" data-cc="30">Ethylene</option>
                    <option value="hills-mccanna-logo.png" data-cc="40">Hills-McCanna</option>
                    <option value="conley-composites-logo.png" data-cc="CC0">Conley Composites</option>
                    <option value="polyvalve-grey-2.png" data-cc="PV0">PolyValve</option>
                    <option value="ramparts-logo-grey.png" data-cc="50">Ramparts Pumps</option>
                    <option value="diamond-fiberglass-tagline-final-grey.png" data-cc="DD">Diamond Fiberglass</option>
                    <option value="endurance-grey-logo.png" data-cc="10">Endurance Composites</option>
                </select>

                <input type="hidden" name="companyCode" id="companyCode" />
                <x-form-field name="initials" id="initials" fieldName="initials" labelText="Initials" required minlength="3" />
                <x-form-submit id="login">Login</x-form-submit>
            </form>

        </div>
    </div>


</x-layout>

