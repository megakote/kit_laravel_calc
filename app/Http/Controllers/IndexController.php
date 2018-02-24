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
        $city_from = $kit->isCity($data['SCITY']);
        $city_to = $kit->isCity($data['RCITY']);
        if (!$city_from && !$city_to) {
            // TODO: Сделать нормальную проверку
            return 'В такой город не доставляем.';
        }

        // Убрать вот это в либу ?
        $data['SLAND'] = $city_from['COUNTRY'];
        $data['SZONE'] = $city_from['TZONEID'];
        $data['SREGIO'] = $city_from['REGION'];

        $data['RSLAND'] = $city_to['COUNTRY'];
        $data['RZONE'] = $city_to['TZONEID'];
        $data['RREGIO'] = $city_to['REGION'];

        $resp = $kit->priceOrder($data);
        return view('index', $resp);
    }
}
