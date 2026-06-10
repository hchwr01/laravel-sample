<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>
    @hasSection('style-override')
        @yield('style-override')
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                background-color: #f9fafb;
                color: #111827;
            }
        </style>
    @endif

    @stack('styles')
</head>
<body>
    {{ $slot }}

    @hasSection('script-override')
        @yield('script-override')
    @else

    @endif

    @stack('scripts')
</body>

</html>
