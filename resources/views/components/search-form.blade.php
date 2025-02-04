@props(['formTitle'])

<div class="mx-auto flex flex-row items-center bg-white border border-gray-200 rounded-lg shadow-sm md:max-w-4xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
    <x-search-form-image imageName="/images/bucket-expansion-joints.jpg" />
    <div class="flex flex-col p-4 leading-normal w-4/6">
        <h5 class="mb-6 mx-8 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $formTitle }}</h5>
        <form class="w-4/6 mx-auto">
            <x-form-field fieldName="part" labelText="Part" />
            <x-form-field fieldName="bin" labelText="Bin" />
            <x-form-submit>Search</x-form-submit>
        </form>
    </div>
</div>
