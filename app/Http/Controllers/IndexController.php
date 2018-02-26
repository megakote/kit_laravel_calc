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

        $data['I_HAVE_DOC'] = ($data['I_HAVE_DOC'] == 'on') ? true : false;

        if (isset($data['DELIVERY']))
            $data['DELIVERY'] = ($data['DELIVERY'] == 'on') ? true : false;

        if (isset($data['PICKUP']))
            $data['PICKUP'] = ($data['PICKUP'] == 'on') ? true : false;

        $resp = $kit->priceOrder($data, $data['SCITY'], $data['RCITY']);

        if (isset($resp['error'])) {
            return $resp['error'];
        }

        return view('index', $resp['data']);
    }
}
