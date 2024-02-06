<?php

namespace App\Services;


use Illuminate\Support\Facades\Http;

class ApiService
{

    public function get_DDI_By_medicine_name($drugId)

    //created By Shahariar
    {

        $response = Http::accept('application/json')
                ->withHeaders(['Authorization' => 'SHAREDKEY 2175:NaRPWnEJnYrEFBqlvCtrfC73R7Nna6ksorbtv91D0GY='])
                ->get("https://api.fdbcloudconnector.com/CC/api/v1_3/DispensableDrugs/$drugId/Interactions?callSystemName=St.+Vincents+Hospital&callid=2175&deptName=Cardiology+Department&limit=50");
            $response_data = $response->getBody()->getContents();
            $dataSet = json_decode($response_data, true);
            return $dataSet;

    }
        

}


