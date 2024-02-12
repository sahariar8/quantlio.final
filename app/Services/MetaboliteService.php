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
               
                dd($response);
                
            }
        }
        
        
        dump($test_detail);

        // $matchMetabolites = array_map(
        //     function ($test) use ($metabolites) {
        //         $foundMetabolite = $metabolites->filter(function ($code) use ($test) {
        //             return strtolower($code->test_name) == strtolower($test) 
        //             || strtolower($code->metabolite) == strtolower($test) 
        //             || strtolower($code->parent) == strtolower($test) ;
        //         })->first();

        //         if ($foundMetabolite) {
        //             return [
        //                 'test_name' => $test,
        //                 'className' => $foundMetabolite->class,
        //                 'metabolite' => $foundMetabolite->metabolite
        //             ];
        //         }

        //         return [
        //             'test_name' => $test,
        //             'className' => null,
        //             'metabolite' => null
        //         ];
        //     },
        //     $tests
        // );

        // $resp = [];

        // foreach ($matchMetabolites as $item) {
        //     $resp[$item['test_name']] = [
        //         "className" => $item['className'],
        //         "metabolite" => $item['metabolite']
        //     ];
        // }

        // dump($resp);



    }

    // public function getAll()
    // {
    //     return DB::table('metabolites')->whereNotNull('metabolite')->get();
    // }

    // public function getMetaboliteByTest($tests)
    // {
    //     $metabolites = $this->getAll();
    //     $matchMetabolites = array_map(
    //         function ($test) use ($metabolites) {
    //             $foundMetabolite = $metabolites->filter(function ($code) use ($test) {
    //                 return strtolower($code->testName) == strtolower($test);
    //             })->first();

    //             if ($foundMetabolite) {
    //                 return [
    //                     'testName' => $test,
    //                     'className' => $foundMetabolite->class,
    //                     'metabolite' => $foundMetabolite->metabolite
    //                 ];
    //             }

    //             return [
    //                 'testName' => $test,
    //                 'className' => null,
    //                 'metabolite' => null
    //             ];
    //         },
    //         $tests
    //     );

    //     $resp = [];

    //     foreach ($matchMetabolites as $item) {
    //         $resp[$item['testName']] = [
    //             "className" => $item['className'],
    //             "metabolite" => $item['metabolite']
    //         ];
    //     }

    //     return $resp;
    // }

    public function _getMetaboliteByTest($test)
    {
        // $arr = [];
        // foreach ($this->_getMetaboliteByTest($test) as $item) {
        //     array_push($arr, $item);
        // }
        // return $arr;
    }
}
