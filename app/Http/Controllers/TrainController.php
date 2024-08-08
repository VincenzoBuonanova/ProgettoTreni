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

    public function refreshTrainsData()
    {
        $response = Http::get('https://italoinviaggio.italotreno.it/api/TreniInCircolazioneService');
        $data = $response->json();

        if (isset($data['TrainSchedules']) && isset($data['LastUpdate'])) {
            $trains = array_map(function ($train) {
                $delayAmount = $train['Distruption']['DelayAmount'] ?? null;
                return array_merge($train, ['DelayAmount' => $delayAmount]);
            }, $data['TrainSchedules']);

            return response()->json([
                'trains' => $trains,
                'lastUpdate' => $data['LastUpdate']
            ]);
        } else {
            return response()->json(['error' => 'Dati non disponibili'], 500);
        }
    }



    public function save(Request $request)
    {
        $data = $request->all();
        $train = Train::updateOrCreate(
            [
                'train_number' => $data['TrainNumber'],
                'departure_date' => $data['DepartureDate']
            ],
            [
                'departure_station_description' => $data['DepartureStationDescription'],
                'arrival_station_description' => $data['ArrivalStationDescription'],
                'arrival_date' => $data['ArrivalDate'],
                'delay_amount' => $data['DelayAmount']
            ]
        );

        return response()->json(['success' => true, 'train' => $train]);
    }


    // Funzione per visualizzare i treni salvati
    public function showSavedTrains(Request $request)
    {
        $date = $request->input('date', null);

        $query = Train::query();

        if ($date) {
            $query->whereDate('DepartureDate', $date);
        }

        $savedTrains = $query->orderBy('DepartureDate', 'desc')->get();

        return view('treni.saved', [
            'savedTrains' => $savedTrains,
            'date' => $date
        ]);
    }
}
