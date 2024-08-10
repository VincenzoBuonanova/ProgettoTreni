<x-layout>
    <div class="container py-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-6">
                <h1>Treni in Circolazione</h1>
            </div>
            <div class="col-6 text-end">
                <h5 id="last-update">Ultimo aggiornamento: {{ $lastUpdate ?? 'N/A' }}</h5>
            </div>
            <div class="row py-4">
                <div class="col-6">
                    <a href="{{ route('trains.saved') }}"><button class="btn btn-outline-danger">Elenco treni salvati</button></a>
                </div>
                <div class="col-6 text-end">
                    <button class="btn btn-outline-primary save-all">Salva tutti i treni</button>
                </div>
            </div>
            <div class="col-12">
                <table id="trains-table">
                    <tbody id="trains-table-body">
                        {{-- tabella coi treni aggiornata al minuto --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>





    {{-- modale per i treni --}}
    <div class="modal fade" id="trainModal" tabindex="-1" aria-labelledby="trainModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="trainModalLabel">Dettagli treno</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="trainModalBody">
                    {{-- dettagli fermate --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</x-layout>
