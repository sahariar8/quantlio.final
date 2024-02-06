<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
// use Exception;

class DBService
{
    public function reportDataSet($orderCode, $dataSet)
    {
        try 
        {
            // Dumping for debugging
            dump($orderCode . ' inside report dataset');
            // dump($dataSet);

            $existingRecord = DB::table('input_datasets')->where('order_code', $orderCode)->first();
            //dump($existingRecord);
            if ($existingRecord) {
            // Update the existing record
                DB::table('input_datasets')->where('order_code', $orderCode)->update(['dataset' => json_encode($dataSet)]);
            } else {
            // Insert a new record
                DB::table('input_datasets')->insert(['order_code' => $orderCode, 'dataset' => json_encode($dataSet)]);
            }

            return response('success');
        } catch (\Illuminate\Http\Client\ConnectionException $ex) {
             Log::channel('error')->error('Problem in fetching data from requested URL');
            // abort(404, 'Problem in fetching data from requested URL');
        }
    }
}