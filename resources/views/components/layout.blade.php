<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Progetto Treni</title>
    {{-- cdn fontawsome  --}}
    <script src="https://kit.fontawesome.com/a8af0967c4.js" crossorigin="anonymous"></script>
    <link rel="website icon" type="png" href="/storage/img/logo.png">

    {{-- mdb css
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet" /> --}}

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

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
