<x-layout>
    <div class="container py-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-6">
                <h1>Treni Salvati</h1>
            </div>
            <div class="col-6 text-end">
                <label for="date-filter">Filtra per data di partenza:</label>
                <input type="date" id="date-filter" class="form-control d-inline w-auto" style="color: blue">
                <button id="filter-date-button" class="btn btn-outline-danger">Filtra</button>
            </div>
            <div class="row py-4">
                <div class="col-6">
                    <a href="{{ route('trains.index') }}">
                        <button class="btn btn-outline-danger">Elenco treni in viaggio</button>
                    </a>
                </div>
                <div class="col-6 text-end">
                    <label for="rows-per-page">Righe per pagina:</label>
                    <select id="rows-per-page" class="form-select d-inline w-auto">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                    </select>
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
        <div id="pagination-controls" class="text-center py-4">
            <button id="prev-page" class="btn btn-outline-secondary" disabled>Precedente</button>
            <span id="page-info">Pagina 1</span>
            <button id="next-page" class="btn btn-outline-secondary">Successiva</button>
        </div>
    </div>
</x-layout>
