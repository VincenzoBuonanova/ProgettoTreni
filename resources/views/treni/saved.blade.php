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
                        {{-- tabella coi treni salvati --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
