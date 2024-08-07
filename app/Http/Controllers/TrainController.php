<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Train;
use Illuminate\Support\Facades\Log;


class TrainController extends Controller
{
    public function home()
    {
        return view('home');
    }

    // todo INDEX funzionante ma senza delay
    // public function index()
    // {
    //     $response = Http::get('https://italoinviaggio.italotreno.it/api/TreniInCircolazioneService');

    //     if ($response->successful()) {
    //         $trains = $response->json()['TrainSchedules'];
    //         return view('treni.index', ['trains' => $trains]);
    //     } else {
    //         return response()->json(['error' => 'Errore nel recupero dei dati'], 500);
    //     }
    // }

    // todo INDEX funzionante con delay
    public function index()
    {
        $response = Http::get('https://italoinviaggio.italotreno.it/api/TreniInCircolazioneService');

        // $data = $response->json();
        $data = json_decode($response, true);
        $lastUpdate = $data['LastUpdate'];

        $trains = array_map(function ($train) {
            $delayAmount = $train['Distruption']['DelayAmount'] ?? null;
            return array_merge($train, ['DelayAmount' => $delayAmount]);
        }, $data['TrainSchedules']);

        return view('treni.index', [
            'trains' => $trains,
            'lastUpdate' => $lastUpdate
        ]);
    }



    public function store(Request $request)
    {
        $data = $request->only(['TrainNumber', 'DepartureStationDescription', 'DepartureDate', 'ArrivalStationDescription', 'ArrivalDate']);

        $delayAmount = $request->input('TrainSchedules.0.Distruption.DelayAmount');

        $data['DelayAmount'] = $delayAmount;

        Train::updateOrCreate(
            ['TrainNumber' => $data['TrainNumber'], 'DepartureDate' => $data['DepartureDate']],
            $data
        );

        return redirect()->route('treni.index');
    }





    public function savedTrains()
    {
        return view('treni.saved', ['trains' => Train::all()]);
    }
}
