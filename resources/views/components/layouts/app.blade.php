<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/gantt-chart/dhtmlxgantt.css') }}" type="text/css">

    @livewireStyles
    @stack('styles')
</head>

<body>
    {{ $slot }}


    <script src="{{ asset('assets/vendor/libs/gantt-chart/dhtmlxgantt.js') }}"></script>

    @stack('scripts')
    @livewireScripts
</body>

</html>
