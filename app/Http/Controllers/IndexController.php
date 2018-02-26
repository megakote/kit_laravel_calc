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

        $resp = $kit->priceOrder($data, $data['SCITY'], $data['RCITY']);

        if (isset($resp['error'])) {
            return $resp['error'];
        }

        return view('index', $resp['data']);
    }
}
