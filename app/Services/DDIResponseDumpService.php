<?php

namespace App\Services;


use Illuminate\Support\Facades\DB;

class DDIResponseDumpService
{

    public function get_DDI_Response($request,$response)

    //created By Shahariar
    {

       $dataInsert = DB::table('ddi_response_dump')->insert(['Request'=>$request,'Response'=>$response]);
       if($dataInsert){
         return 'Data Insert SuccessFully';
       }else{
         return 'Data not Inserted';
       }


    }
        

}


