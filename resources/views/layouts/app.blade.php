<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }}</title>
    <!-- Include CSS using Vite -->
    @vite('resources/sass/app.scss')
</head>
<body>
    <!-- Include navigation from layouts.nav -->
    @include('layouts.nav')

    <!-- Content section -->
    @yield('content')

    <!-- Include JS using Vite -->
    @vite('resources/js/app.js')
</body>
</html>
