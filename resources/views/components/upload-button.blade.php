<div class="block mx-auto w-[16rem] mt-8">
    <button {{ $attributes->merge(['id', 'name', 'class' => 'max-w-6xl w-[16rem] px-6 py-3.5 text-base font-bold text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 ']) }} type="submit" >
        <span class="max-w-sm mx-2 block w-6 h-6">
            <x-zondicon-upload />
        </span>
        {{ $slot }}
    </button>
</div>
