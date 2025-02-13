@props(['formTitle', 'imageName'])

<div class="mt-4 mx-auto flex flex-row items-center bg-white border border-gray-200 rounded-lg shadow-sm md:max-w-4xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
    <x-search-form-image imageName="{{ $imageName }}" />
    <div class="flex flex-col leading-normal w-4/6">
        <h5 class="mb-4 mt-2 mx-8 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $formTitle }}</h5>
        {{ $slot }}
    </div>
</div>
