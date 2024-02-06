<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RequestDataSetController extends Controller
{
    public function index()
    {
        $dataSet = DB::table('input_datasets')->get();
        return view('requestDataSet', ['dataSets' => $dataSet])->with('i');
    }
}