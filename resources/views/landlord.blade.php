@php ($lang = str_replace('_', '-', app()->getLocale()))
<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title inertia>{{ config('app.name', 'Aiku') }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="{{ mix('css/l/app.css') }}">
    @routes
    @translations($lang)
    <script src="{{ mix('js/l/app.js') }}" defer></script>
</head>
<body class="font-sans antialiased">
@inertia
</body>
</html>
