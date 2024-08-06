<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treni Salvati</title>
</head>
<body>
    <h1>Treni Salvati</h1>
    <button onclick="location.href='{{ route('trains.index') }}'">Visualizza Treni in Circolazione</button>
    <form method="GET" action="{{ route('trains.saved') }}">
        <label for="date">Filtra per Data:</label>
        <input type="date" id="date" name="date" value="{{ request('date') }}">
        <button type="submit">Filtra</button>
    </form>
    <ul>
        @foreach ($trains as $train)
            <li>
                <p>Numero Treno: {{ $train->train_number }}</p>
                <p>Partenza: {{ $train->departure_station }} alle {{ $train->departure_time }}</p>
                <p>Arrivo: {{ $train->arrival_station }} alle {{ $train->arrival_time }}</p>
                <p>Ritardo: {{ $train->delay }} minuti</p>
                <p>Salvato il: {{ $train->created_at }}</p>
            </li>
        @endforeach
    </ul>
</body>
</html>






    {{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treni Salvati</title>
</head>
<body>
    <h1>Treni Salvati</h1>
    <ul>
        @foreach ($trains as $train)
            <li>
                Treno {{ $train->train_number }} da {{ $train->departure_station }} ({{ $train->departure_time }}) a {{ $train->arrival_station }} ({{ $train->arrival_time }})
                - {{ $train->disruption > 10 ? "Ritardo: {$train->disruption} minuti" : $train->disruption < 0 ? "In anticipo: " . abs($train->disruption) . " minuti" : 'In orario' }}
            </li>
        @endforeach
    </ul>
</body>
</html> --}}
