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

        $test_detail = $this->getAll();//get all from DB

        foreach($test_detail  as $test){
            if(in_array($test->test_name,$testNames)){
                $metabolite = $test->metabolite;
                $parent = $test->parent;
                if($parent != null && $metabolite != null){
                    $response[] = [
                        'testname' => $test->test_name,
                        'metabolite' => $metabolite,
                        'parent' => $parent
                    ];
                }elseif($parent == null && $metabolite != null){
                    $response[] = [
                        'testname' => $test->test_name,
                        'metabolite' => $metabolite,
                        
                    ]; 
                }elseif($parent != null && $metabolite == null){
                    $response[] = [
                        'testname' => $test->test_name,
                        'parent' => $parent   
                    ];
                }
                
            }
        }
        return $response;

    }


}
