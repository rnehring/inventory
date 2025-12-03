<div class="w-1/3 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mx-2 my-2">
    <ul class="flex flex-wrap text-sm font-medium text-center text-gray-200 border-b border-gray-200 rounded-t-lg bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:bg-gray-800" id="defaultTab" data-tabs-toggle="#defaultTabContent" role="tablist">
        <p class="px-4 py-6">{{ $chartTitle }}</p>
    </ul>
    <div id="defaultTabContent">
        <div class="p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800 dark:text-gray-200" id="about" role="tabpanel" aria-labelledby="about-tab">
            <canvas class="max-w-lg dark:text-gray-200" id="{{ $chartCanvasId }}"></canvas>
        </div>

    </div>
</div>
