<div class="block mx-auto w-[14rem] mt-8">
    <button
        type="submit"
        {{ $attributes->merge([
            'class' => 'max-w-6xl w-[14rem] px-6 py-3.5 text-base font-medium text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-left dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800'
        ]) }}
    >
        <svg class="w-6 h-6 ml-6 mr-3" id="Layer_1"
             style="enable-background:new 0 0 128 128;"
             version="1.1"
             viewBox="0 0 128 128"
             xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink">
            <style type="text/css">
                .st0{fill:#ffffff;}
            </style>
            <circle cx="64" cy="64" r="64" fill="oklch(72.3% 0.219 149.579)"/>
            <path fill="white" class="st0" d="M103,57H71V25c0-0.6-0.4-1-1-1H58c-0.6,0-1,0.4-1,1v32H25c-0.6,0-1,0.4-1,1v12c0,0.6,0.4,1,1,1h32v32  c0,0.6,0.4,1,1,1h12c0.6,0,1-0.4,1-1V71h32c0.6,0,1-0.4,1-1V58C104,57.4,103.6,57,103,57z"/>
        </svg>
        {{ $slot }}
    </button>
</div>
