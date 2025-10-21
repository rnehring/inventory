@if(! request()->is('/'))

<header class="bg-white shadow mx-auto px-4 py-6 sm:px-6 lg:px-8">
@if(request()->is('data'))
    <div class="flex justify-between max-w-10xl mx-auto">
@else
    <div class="flex justify-between max-w-9xl mx-auto">
@endif

    <div class="">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $slot }}</h1>
    </div>

    @if(request()->is('data'))

    @endif

    </div>
</header>

    @endif
