<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
// use Exception;

class InsertOrderCodeService

{
    public function insertOrderCode($sampleId,$workstatus= 0,$numberOfFailur= 0)
    {
        DB::table('order_code_queues')
        ->insert([
            'orderCode' => $sampleId, 'workStatus' => $workstatus, 'numberOfFailur'=> $numberOfFailur
        ]);
        return true;
    }
}