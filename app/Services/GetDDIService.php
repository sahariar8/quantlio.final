<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\ApiService;

class GetDDIService
{
    public function checkData($drugIds)

    // {
    //     //created By Shahariar
        
    //     $result = DB::table('drug_interactions')
    //     ->where('drug_id', '=', $drugId)
    //     ->where('updated_at', '>', now()->subDays(7))
    //     ->get();
    //     // dd($result);

    //     if (count($result) > 0) 
    //     {
    //         print_r('sahariar');

    //         return $result;

    //     }else{
    //         $apiResponse =(new ApiService)->get_DDI_By_medicine_name($drugId);
          
    //         if($apiResponse){
    //             $data = DB::table('drug_interactions')
    //             ->where(['drug_id'=>$drugId])
    //             ->first();

    //             if ($data) {

    //                 DB::table('drug_interactions')
    //                 ->where('drug_id', $drugId)
    //                 ->update(['dataset' => json_encode($data)]);

    //                 print_r('updated successfully');

    //             } else {
            
    //                 DB::table('drug_interactions')
    //                 ->insert(['drug_id' => $drugId, 'dataset' => json_encode($data)]);

    //                 print_r('inserted successfully');

    //             }
    
    //         }
    //         return $apiResponse;
    //     }
    // }
    {
        //cath data which get from db
        $data = [];

        //
        $results = DB::table('drug_interactions')
                    ->whereIn('drug_id', $drugIds)
                    ->where('updated_at', '>', now()->subDays(7))
                    ->get();
        //Catch DrugId which data not get from DB
        $catchIds = [];
        //
        foreach($results as $result){
        
            $get_Drug_Id_Data = $result->drug_id;
            array_push($catchIds, $get_Drug_Id_Data);
            $data[] = $result;
        }

        $filteredDrugIds = array_diff($drugIds, $catchIds);
        // dd($filteredDrugIds,$data);
        
        foreach($filteredDrugIds as $filterId){
            $apiResponse =(new ApiService)->get_DDI_By_medicine_name($filterId);
            $data[]  = (object) $apiResponse;

            //check
            $final = DB::table('drug_interactions')->updateOrInsert(
                ['drug_id' => $filterId],
                [
                    'drug_id' => $filterId,
                    'dataset' => json_encode($apiResponse),
                    'updated_at' => now(),
                ]
            );

        }
        return $data;


    }
}