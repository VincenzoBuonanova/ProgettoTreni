<?php

namespace App\Http\Controllers;

use App\Models\Train;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class TrainController extends Controller
{
    public function home()
    {
        return view('home');
    }


    // todo Elenco di tutti i treni in circolazione
    public function index()
    {
        $response = Http::get('https://italoinviaggio.italotreno.it/api/TreniInCircolazioneService');
        $data = $response->json();

        if (isset($data['IsEmpty']) && $data['IsEmpty']) {
            $filePath = resource_path('views/TreniInCircolazioneServiceStatico.json');

            if (!file_exists($filePath)) {
                return response()->json(['error' => 'File non trovato'], 404);
            }

            $json = file_get_contents($filePath);
            $data = json_decode($json, true);
        }

        $lastUpdate = $data['LastUpdate'] ?? 'N/A';
        $trains = array_map(function ($train) {
            $delayAmount = $train['Distruption']['DelayAmount'] ?? null;
            return array_merge($train, ['DelayAmount' => $delayAmount]);
        }, $data['TrainSchedules'] ?? []);

        return view('treni.index', [
            'trains' => $trains,
            'lastUpdate' => $lastUpdate
        ]);
    }


    // todo Funzione per refresharli
    public function refreshTrainsData()
    {
        $response = Http::get('https://italoinviaggio.italotreno.it/api/TreniInCircolazioneService');
        $data = $response->json();

        if (isset($data['IsEmpty']) && $data['IsEmpty']) {
            $filePath = resource_path('views/TreniInCircolazioneServiceStatico.json');

            if (!file_exists($filePath)) {
                return response()->json(['error' => 'File non trovato'], 404);
            }

            $json = file_get_contents($filePath);
            $data = json_decode($json, true);
        }

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


    // todo Funzione per salvare un treno
    public function saveTrain(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TrainNumber' => 'required|string|max:255',
            'DepartureStationDescription' => 'required|string|max:255',
            'DepartureDate' => 'required|date_format:H:i',
            'ArrivalStationDescription' => 'required|string|max:255',
            'ArrivalDate' => 'required|date_format:H:i',
            'DelayAmount' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        $currentDate = now()->format('Y-m-d');
        $currentTime = now()->format('H:i');

        // dd($request->all());
        $train = Train::updateOrCreate(
            [
                'train_number' => $data['TrainNumber'],
                'departure_date' => $data['DepartureDate'],
            ],
            [
                'departure_station_description' => $data['DepartureStationDescription'],
                'arrival_station_description' => $data['ArrivalStationDescription'],
                'arrival_date' => $data['ArrivalDate'],
                'delay_amount' => $data['DelayAmount'] ?? null,
                'saved_at_date' => $currentDate,
                'saved_at_time' => $currentTime
            ]
        );

        return response()->json([
            'success' => 'Treno salvato con successo',
            'train' => $train
        ],
            200
        );
    }


    // todo Elenco treni salvati
    public function trainsSaved()
    {
        $trains = Train::all();
        return view('treni.saved', [
            'trains' => $trains
        ]);
    }
}