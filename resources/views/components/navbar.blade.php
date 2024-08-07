<nav class="navbar navbar-expand-lg bg-nav px-3" style="background-color: #f0f0f0;">
    <div class="container-fluid">
        <a class="navbar-brand rounded p-2" style="background-color:darkred" href="{{ route('home') }}"><img src="https://italoinviaggio.italotreno.com/-/media/NewItalotreno-Images/logo.svg" alt="logo italo" class="logo"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link" href="{{ route('home') }}">Home</a>
                <a class="nav-link" href="{{ route('trains.index') }}">Elenco Treni</a>
                <a class="nav-link" href="{{ route('trains.saved') }}">Treni Salvati</a>
            </div>
        </div>
    </div>
</nav>