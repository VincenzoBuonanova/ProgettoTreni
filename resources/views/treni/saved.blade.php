<x-layout>
    <div class="container py-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-6">
                <h1>Treni Salvati</h1>
            </div>
            <div class="col-6 text-end">
                <h5 id="last-update">
                    Cerca una data specifica
                </h5>
            </div>
            <div class="row py-4">
                <div class="col-6">
                    <a href="{{ route('trains.index') }}">
                        <button class="btn btn-outline-danger">Elenco treni in viaggio</button>
                    </a>
                </div>
                <div class="col-6 text-end">
                    <a href="#">
                        <button class="btn btn-outline-primary">Cancella tutti i treni</button>
                    </a>
                </div>
            </div>
            <div class="col-12">
                <table id="saved-trains-table">
                    <tbody id="saved-trains-table-body">
                        @forelse ($trains as $train)
                            <tr>
                                <td style="color: #a30000;">
                                    <i class="fa-solid fa-train fa-xl"></i> Italo {{ $train->train_number }}
                                </td>
                                <td>
                                    {{ $train->departure_station_description }}
                                    <strong>{{ $train->departure_date }}</strong>
                                    <i class="fa-solid fa-arrow-right"></i>
                                    {{ $train->arrival_station_description }}
                                    <strong>{{ $train->arrival_date }}</strong>
                                </td>
                                <td>
                                    Salvato il {{ $train->created_at }} alle ore {{ $train->created_at->format('H:i') }}.
                                </td>
                                <td id="saved-delay-amount">
                                    @if ($train->delay_amount < 0)
                                        <i class="fa-solid fa-circle" style="color: green;"></i> In anticipo
                                    @elseif ($train->delay_amount < 10)
                                        <i class="fa-solid fa-circle" style="color: green;"></i> In orario
                                    @else
                                        <i class="fa-solid fa-circle" style="color: red;"></i> Ritardo {{ $train->delay_amount }} minuti
                                    @endif
                                </td>
                                <td>
                                    <button>Dettagli</button>
                                    {{-- <button onclick='deleteTrain({{ $train->id }})'>Elimina</button> --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    Nessun treno salvato.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
