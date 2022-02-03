@php ($lang = str_replace('_', '-', app()->getLocale()))
<!DOCTYPE html>
<html lang="{{ $lang }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    @routes
    @vite(js/t/app.js)
</head>
<body class="font-sans antialiased h-full overflow-hidden">
@inertia
</body>
</html>
