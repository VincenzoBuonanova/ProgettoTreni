<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Train;

class TrainController extends Controller
{
    public function index()
    {
        $response = Http::get('https://italoinviaggio.italotreno.it/api/TreniInCircolazioneService');

        if ($response->successful()) {
            $trains = $response->json()['TrainSchedules'];
            return view('treni.index', ['trains' => $trains]);
        } else {
            // In caso di errore nella richiesta alla API
            return response()->json(['error' => 'Errore nel recupero dei dati'], 500);
        }
    }

    public function store(Request $request)
    {
        // Salvataggio dei dati del treno nel database
        $data = $request->only(['train_number', 'departure_station', 'departure_time', 'arrival_station', 'arrival_time', 'delay']);

        Train::updateOrCreate(
            ['train_number' => $data['train_number'], 'departure_time' => $data['departure_time']],
            $data
        );

        return redirect()->route('treni.index');
    }

    public function fetchTrains()
    {
        $response = Http::get('https://italoinviaggio.italotreno.it/api/TreniInCircolazioneService');
        $data = $response->json();

        // Parsa i dati e salva i treni nel database
        $trains = collect($data['TrainSchedules'])->map(function ($train) use ($data) {
            return [
                'train_number' => $train['TrainNumber'],
                'departure_station' => $train['Stations'][0]['StationName'],
                'arrival_station' => end($train['Stations'])['StationName'],
                'departure_time' => $train['Stations'][0]['ScheduledDeparture'],
                'arrival_time' => end($train['Stations'])['ScheduledArrival'],
                'disruption' => $train['Disruption'],
            ];
        });

        Train::truncate();
        Train::insert($trains->toArray());

        return response()->json([
            'last_update' => $data['LastUpdate'],
            'trains' => Train::all()
        ]);
    }

    public function savedTrains()
    {
        return view('treni.saved', ['trains' => Train::all()]);
    }
}
