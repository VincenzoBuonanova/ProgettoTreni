<x-layout>
    <div class="container py-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-6">
                <h1>Treni in Circolazione</h1>
            </div>
            <div class="col-6 text-end">
                <h5>Ultimo aggiornamento: <span id="last-update">{{ $lastUpdate }}</span></h5>
            </div>
            <div class="row py-4">
                <div class="col-6">
                    <a href="{{ route('trains.saved') }}"><button class="btn btn-outline-danger">Elenco treni salvati</button></a>
                </div>
                <div class="col-6 text-end">
                    <a href="#"><button class="btn btn-outline-primary">Salva tutti i treni</button></a>
                </div>
            </div>
            <div class="col-12">
                <table id="trains-table">
                    <thead>
                        <tr>
                            {{-- <th>Numero Treno</th>
                            <th>Stazione di Partenza, Orario, Arrivo, Orario</th>
                            <th>Ritardo?</th>
                            <th>Salva il Treno</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trains as $train)
                        <tr>
                            <td style="color: #a30000;"><i class="fa-solid fa-train fa-xl"></i> Italo {{ $train['TrainNumber'] }}</td>
                            <td>{{ $train['DepartureStationDescription'] }} <strong>{{ $train['DepartureDate'] }}</strong> <i class="fa-solid fa-arrow-right"></i> {{ $train['ArrivalStationDescription'] }} <strong>{{ $train['ArrivalDate'] }}</strong></td>
                            <td>
                                @if ($train['DelayAmount'] < 0)
                                <i class="fa-solid fa-circle" style="color: green;"></i> In anticipo
                                @elseif ($train['DelayAmount'] < 10)
                                <i class="fa-solid fa-circle" style="color: green;"></i> In orario
                                @else
                                <i class="fa-solid fa-circle" style="color: red;"></i> Ritardo {{ $train['DelayAmount'] }} minuti
                                @endif
                            </td>
                            <td><a href="#"><i class="fa-solid fa-circle-info"></i></a> <a href="#"><i class="fa-solid fa-floppy-disk"></i></a> <a href="#"><i class="fa-regular fa-floppy-disk"></i></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layout>
