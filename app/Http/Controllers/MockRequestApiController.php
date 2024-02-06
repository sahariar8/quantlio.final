<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class MockRequestApiController extends Controller
{
    public function order_code()
    {
        $orderCode = DB::table('input_datasets')->pluck('order_code');
        $count = count($orderCode);
        $dataSets = json_encode($orderCode, true);
        return ['status' => 'ok','total_count'=>$count,'result_count'=>$count,'results' => $dataSets];
     }

     public function dataSet($order_code)
     {
       $dataset_single = DB::table('input_datasets')->select('dataset')->where('order_code', $order_code)->first();
        $cleanDataSet = array();
        $cleanedData = stripslashes((string)json_encode($dataset_single->dataset));
      
        $trimmedStr = trim($cleanedData, '"');
        //   return $cleanedData;
        $cleanedJson = json_decode($trimmedStr, JSON_PRETTY_PRINT);
        $cleanDataSet[] = $cleanedJson;

        return [
            'status' => 'ok',
            'total_count'=>5,
            'result_count'=>5,
            'results' => $cleanDataSet
        ];
    }
}