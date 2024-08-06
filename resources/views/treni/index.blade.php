<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Treni in Circolazione</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <h1>Treni in Circolazione</h1>
    <table id="trains-table">
        <thead>
            <tr>
                <th>Numero Treno</th>
                <th>Stazione di Partenza, orario, arrivo, orario</th>
                {{-- <th>Orario di Partenza</th>
                <th>Stazione di Arrivo</th>
                <th>Orario di Arrivo</th> --}}
                <th>Ritardo</th>
                <th>Azione</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($trains as $train)
                <tr>
                    <td>Italo {{ $train['TrainNumber'] }}</td>
                    <td><button>Dettagli</button></td>
                    <td>{{ $train['DepartureStationDescription'] }} <strong>{{ $train['DepartureDate'] }}</strong> <i class="fa-solid fa-arrow-right"></i> {{ $train['ArrivalStationDescription'] }} <strong>{{ $train['ArrivalDate'] }}</strong></td>
                </tr>
                @if (!$loop->last)
                    <hr>
                @endif
            @endforeach
        </tbody>
    </table>
</body>
</html>