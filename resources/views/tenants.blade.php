@php ($lang = str_replace('_', '-', app()->getLocale()))
<!DOCTYPE html>
<html lang="{{ $lang }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    @routes
    @vite
</head>
<body class="h-full">
@inertia
</body>
</html>
