<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class GetDatasetService
{
    public function getDataset($orderCode) 
    {
        try{
            dump('get order code :: ' . $orderCode);
                $getData = DB::table('input_datasets')->where('order_code', $orderCode)->first();
                dump('got data', $getData);
                if ($getData) {
                    // Convert JSON to array
                    $dataArray = json_decode($getData->dataset, true);
                    return $dataArray;
                    // dump($dataArray,'array of data');
                }
                else {
                    // Handle the case where no data is found
                    return response('Data not found for the specified order code', 404);
                }
        } 
        catch(\Exception $ex)
        {
            dump($ex->getMessage());
        }
    }
}