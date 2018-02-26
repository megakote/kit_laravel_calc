<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use slowdream\kit_laravel\Kit;

class IndexController extends Controller
{

    public function index()
    {
        return view('index');
    }

    public function execute (Request $request, Kit $kit) {
        $data = $request->all();
        unset($data['_token']);

        if (!$city_from = $kit->isCity($data['SCITY'])) {
            return 'Не работаем с '. $data['SCITY'];
        }
        if (!$city_to = $kit->isCity($data['RCITY'])) {
            return 'Не работаем с '. $data['RCITY'];
        }

        $resp = $kit->priceOrder($data, $city_from, $city_to);

        return view('index', $resp['data']);
    }
}
