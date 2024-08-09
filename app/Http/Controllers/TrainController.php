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

    // todo INDEX funzionante con delay
    // public function index()
    // {
    //     $response = Http::get('https://italoinviaggio.italotreno.it/api/TreniInCircolazioneService');

    //     $data = json_decode($response, true);
    //     $lastUpdate = $data['LastUpdate'];

    //     $trains = array_map(function ($train) {
    //         $delayAmount = $train['Distruption']['DelayAmount'] ?? null;
    //         return array_merge($train, ['DelayAmount' => $delayAmount]);
    //     }, $data['TrainSchedules']);

    //     return view('treni.index', [
    //         'trains' => $trains,
    //         'lastUpdate' => $lastUpdate
    //     ]);
    // }
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

    // public function refreshTrainsData()
    // {
    //     $response = Http::get('https://italoinviaggio.italotreno.it/api/TreniInCircolazioneService');
    //     $data = $response->json();

    //     if (isset($data['TrainSchedules']) && isset($data['LastUpdate'])) {
    //         $trains = array_map(function ($train) {
    //             $delayAmount = $train['Distruption']['DelayAmount'] ?? null;
    //             return array_merge($train, ['DelayAmount' => $delayAmount]);
    //         }, $data['TrainSchedules']);

    //         return response()->json([
    //             'trains' => $trains,
    //             'lastUpdate' => $data['LastUpdate']
    //         ]);
    //     } else {
    //         return response()->json(['error' => 'Dati non disponibili'], 500);
    //     }
    // }
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



    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TrainNumber' => 'required|string|max:255',
            'DepartureStationDescription' => 'required|string|max:255',
            'DepartureDate' => 'required|date_format:H:i:s',
            'ArrivalStationDescription' => 'required|string|max:255',
            'ArrivalDate' => 'required|date_format:H:i:s',
            'TrainSchedules.0.Distruption.DelayAmount' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        $train = Train::updateOrCreate(
            [
                'TrainNumber' => $data['TrainNumber'],
                'DepartureDate' => $data['DepartureDate'],
            ],
            [
                'DepartureStationDescription' => $data['DepartureStationDescription'],
                'ArrivalStationDescription' => $data['ArrivalStationDescription'],
                'ArrivalDate' => $data['ArrivalDate'],
                'DelayAmount' => $data['TrainSchedules.0.Distruption.DelayAmount'] ?? null,
            ]
        );

        return response()->json([
            'success' => 'Treno salvato con successo',
            'train' => $train
        ],
            200
        );
    }


    public function trainsSaved()
    {
        $trains = Train::all();
        return view('treni.saved', [
            'trains' => $trains
        ]);
    }
}
