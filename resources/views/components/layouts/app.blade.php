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

    <script src="https://code.jquery.com/jquery-3.3.1.min.js?v=5.2.4"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.js?v=5.2.4"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css?v=5.2.4">
    <script src="{{ asset('assets/vendor/libs/gantt-chart/dhtmlxgantt.js') }}"></script>
    <script src="{{ asset('assets/plugins/fabricjs/fabric.js') }}"></script>
    @livewireScripts

    @stack('scripts')
</body>

</html>
