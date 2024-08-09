<x-layout>
    <div class="container py-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-6">
                <h1>Treni Salvati</h1>
            </div>
            <div class="col-6 text-end">
                <h5 id="last-update">Ultimo aggiornamento: {{ $lastUpdate ?? 'N/A' }}</h5>
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
                <table id="saved-trains-table">
                    <thead>
                        {{-- <tr>
                            <th>Numero Treno</th>
                            <th>Stazione di Partenza, Orario, Arrivo, Orario</th>
                            <th>Ritardo?</th>
                            <th>Salva il Treno</th>
                        </tr> --}}
                    </thead>
                    <tbody id="saved-trains-table-body">
                        {{-- tabella coi treni aggiornata al minuto --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
