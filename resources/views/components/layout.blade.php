<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <meta http-equiv="refresh" content="60"> --}}
    <title>Progetto Treni</title>
    {{-- cdn fontawsome  --}}
    <script src="https://kit.fontawesome.com/a8af0967c4.js" crossorigin="anonymous"></script>
    <link rel="website icon" type="png" href="/storage/img/logo.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <x-navbar />

    <div class="min-vh-100">
        {{ $slot }}
    </div>

    <x-footer />
</body>

</html>
