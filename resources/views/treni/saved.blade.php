<x-layout>
    <div class="container py-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-6">
                <h1>Treni Salvati</h1>
            </div>
            <div class="col-6 text-end">
                {{-- <form method="GET" action="{{ route('trains.saved') }}">
                    <input type="date" name="date" value="{{ $date ?? '' }}">
                    <button type="submit" class="btn btn-outline-primary">Filtra per data</button>
                </form> --}}
            </div>
        </div>
        <div class="col-12">
            <table id="trains-table">
                <thead>
                </thead>
                <tbody id="trains-table-body">
                    {{-- @foreach ($savedTrains as $train)
                    <tr>
                        <td>{{ $train->train_number }}</td>
                        <td>{{ $train->departure_station }} <strong>{{ $train->departure_date }}</strong> <i class="fa-solid fa-arrow-right"></i> {{ $train->arrival_station }} <strong>{{ $train->arrival_date }}</strong></td>
                        <td>
                            @if ($train->delay_amount < 0)
                            <i class="fa-solid fa-circle" style="color: green;"></i> In anticipo
                            @elseif ($train->delay_amount < 10)
                            <i class="fa-solid fa-circle" style="color: green;"></i> In orario
                            @else
                            <i class="fa-solid fa-circle" style="color: red;"></i> Ritardo {{ $train->delay_amount }} minuti
                            @endif
                        </td>
                        <td>{{ $train->saved_at }}</td>
                    </tr>
                    @endforeach --}}
                </tbody>
            </table>
        </div>
    </div>
</x-layout>