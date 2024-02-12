<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

//created by shahariar
class MetaboliteService
{
    public function getAll()
    {
        return DB::table('test_details')->get();
    }

    public function getMetaboliteByTest($testNames)
    {

        $test_detail = $this->getAll();
        foreach($test_detail  as $test){
            if(in_array($test->test_name,$testNames)){
                $metabolite = $test->metabolite;
                $parent = $test->parent;
                if($parent != null){
                    $response[] = [
                        'testname' => $test->test_name,
                        'metabolite' => $metabolite,
                        'parent' => $parent
                    ];
                }else{
                    $response[] = [
                        'testname' => $test->test_name,
                        'metabolite' => $metabolite,
                        
                    ]; 
                }
                
            }
        }
        return $response;

    }


}
