<x-layout>
    <div class="container">
        <div class="row my-5 justify-content-center">
            <div class="col-12 col-md-10 text-center">
                <h1 class="display-2 text-danger">Progetto colloquio tecnico</h1>
                <h3 class="display-4 fst-italic">Vincenzo Buonanova</h3>
            </div>
        </div>
        <div class="row justify-content-center text-center">
            <div class="col-12 col-md-4 text-md-end py-3">
                <a href="{{ route('trains.index') }}"><button class="btn btn-outline-primary">Elenco treni</button></a>
            </div>
            <div class="col-12 col-md-4 text-md-start py-3">
                <a href="{{ route('trains.saved') }}"><button class="btn btn-outline-danger">Elenco treni salvati</button></a>
            </div>
        </div>
    </div>
</x-layout>