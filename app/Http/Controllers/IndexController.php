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

    public function execute (Request $request) {
        $kit = new Kit();
        $data = $kit->priceOrder($request->all());
        return view('index', $data);
    }
}
