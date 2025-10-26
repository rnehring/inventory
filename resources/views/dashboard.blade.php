<x-layout>

    <x-slot:header>
        <x-header>Dashboard</x-header>
    </x-slot:header>

    <x-chart-container>
        <x-slot:categoryName>Inventory Count Data</x-slot:categoryName>

        <x-dashboard-chart>
            <x-slot:chartTitle>Counts By User All Time</x-slot:chartTitle>
            <x-slot:chartCanvasId>counts-by-user-all-time</x-slot:chartCanvasId>
        </x-dashboard-chart>

        <x-dashboard-chart>
            <x-slot:chartTitle>Counts By User Yesterday</x-slot:chartTitle>
            <x-slot:chartCanvasId>counts-by-user-yesterday</x-slot:chartCanvasId>
        </x-dashboard-chart>

        <x-dashboard-chart>
            <x-slot:chartTitle>Percentage Complete By Company</x-slot:chartTitle>
            <x-slot:chartCanvasId>percent-by-company</x-slot:chartCanvasId>
        </x-dashboard-chart>

    </x-chart-container>

{{--    <x-chart-container>--}}
{{--        <x-slot:categoryName>Inventory Pre Count Data</x-slot:categoryName>--}}

{{--        <x-dashboard-chart>--}}
{{--            <x-slot:chartTitle>Bins Verified By User (All Time)</x-slot:chartTitle>--}}
{{--            <x-slot:chartCanvasId>bins-verified-all-time</x-slot:chartCanvasId>--}}
{{--        </x-dashboard-chart>--}}

{{--        <x-dashboard-chart>--}}
{{--            <x-slot:chartTitle>Bins Verified By User (Yesterday)</x-slot:chartTitle>--}}
{{--            <x-slot:chartCanvasId>bins-verified-yesterday</x-slot:chartCanvasId>--}}
{{--        </x-dashboard-chart>--}}

{{--        <x-dashboard-chart>--}}
{{--            <x-slot:chartTitle>Bins Verified By Company</x-slot:chartTitle>--}}
{{--            <x-slot:chartCanvasId>bins-verified-by-company</x-slot:chartCanvasId>--}}
{{--        </x-dashboard-chart>--}}

{{--    </x-chart-container>--}}

</x-layout>

