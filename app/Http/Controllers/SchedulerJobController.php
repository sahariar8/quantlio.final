<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Exception;
use  Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Services\PDFGenerationService;
use App\Services\SFTPService;
use App\Services\JsonErrorService;
use App\Services\InsertOrderCodeService;

class SchedulerJobController extends Controller
{
    public $uuids = []; 
    public $singleUuid = '';

    protected PDFGenerationService  $_pdfGenerationService;
    protected SFTPService $_SFTPService;

    function __construct()
     {
        $this->_pdfGenerationService = new PDFGenerationService();
        $this->_SFTPService = new SFTPService();
     }

    public function getAndStoreRequestedJsonDataSet()
    {
        $this->_pdfGenerationService = new PDFGenerationService();

        ini_set('max_execution_time', '0');
        $tableName = 'order_code_queues';
        try
        {
            $files = $this->_SFTPService->getListAllInFilesInsFTPServer();
            dump($files);
            foreach ($files as $key=>$file) {
                 dump($file);
                // $data = (new JsonErrorService)->replaceMissingValues($file);
                // dd($data);
                // $file = (new JsonErrorService)->check($data);
                // dd($data1);
                // $data = json_decode($file, true);
                // dd($file);
                if($data = json_decode($file, true)){
                    // dd($data);
                    if ($data !== null) {
                        // Access the sampleid
                        $sampleId = $data['sampleid'];
                        echo "Sample ID: $sampleId<br>";

                        //insert the record if not exist
                        $insert1 = DB::table('input_datasets')
                        ->insert([
                            'order_code' => $sampleId, 'dataset' => json_encode($data)
                        ]);
                        //service calling for insert oeder code into order_code_queue table
                        $insert2 = ( new InsertOrderCodeService)->insertOrderCode($sampleId);
    
                        if($insert1 && $insert2){
                            (new SFTPService)->moveInFileToArchive($key);
                            print_r('insert hoise');
    
                        }else{
    
                            echo 'inserted failed into db6';
                        }

        
                        // $existingRecord = DB::table('input_datasets')
                        //     ->where('order_code', $sampleId)
                        //     ->first();
    
                        // if($existingRecord){
                        //     $insert4 = DB::table('input_datasets')
                        //         ->where('order_code', $sampleId)
                        //         ->update([
                        //             'dataset' => json_encode($data)
                        //         ]);
    
                        //     $insert3 = ( new InsertOrderCodeService)->insertOrderCode($sampleId);
                        //     if($insert4 && $insert3){
        
                        //         (new SFTPService)->moveInFileToArchive($key);
                        //         print_r('update hoise');
        
                        //     }else{
        
                        //         echo 'inserted failed into db5';
                        //     }
    
                        
                        // } else {
                        // //insert the record if not exist
                        //     $insert1 = DB::table('input_datasets')
                        //     ->insert([
                        //         'order_code' => $sampleId, 'dataset' => json_encode($data)
                        //     ]);
                        //     //service calling for insert oeder code into order_code_queue table
                        //     $insert2 = ( new InsertOrderCodeService)->insertOrderCode($sampleId);
        
                        //     if($insert1 && $insert2){
                        //         (new SFTPService)->moveInFileToArchive($key);
                        //         print_r('insert hoise');
        
                        //     }else{
        
                        //         echo 'inserted failed into db6';
                        //     }
                        // }
                    }
                }else{
                    $data = (new JsonErrorService)->replaceMissingValues($file);
                    // dd($data,'sa');
                    $file = (new JsonErrorService)->check($data);
                    $data = json_decode($file, true);
                    if ($data !== null) {
                        // Access the sampleid
                        $sampleId = $data['sampleid'];
                        echo "Sample ID: $sampleId<br>";

                        //insert the record if not exist
                        $insert1 = DB::table('input_datasets')
                        ->insert([
                            'order_code' => $sampleId, 'dataset' => json_encode($data)
                        ]);
                        //service calling for insert oeder code into order_code_queue table
                        $insert2 = ( new InsertOrderCodeService)->insertOrderCode($sampleId);
    
                        if($insert1 && $insert2){
                            (new SFTPService)->moveInFileToArchive($key);
                            print_r('insert hoise');
    
                        }else{
    
                            echo 'inserted failed into db8';
                        }
        
                        // $existingRecord = DB::table('input_datasets')
                        //     ->where('order_code', $sampleId)
                        //     ->first();
    
                        // if($existingRecord){
                        //     $insert4 = DB::table('input_datasets')
                        //         ->where('order_code', $sampleId)
                        //         ->update([
                        //             'dataset' => json_encode($data)
                        //         ]);
    
                        //     $insert3 = ( new InsertOrderCodeService)->insertOrderCode($sampleId);
                        //     if($insert4 && $insert3){
        
                        //         (new SFTPService)->moveInFileToArchive($key);
                        //         print_r('update hoise');
        
                        //     }else{
        
                        //         echo 'inserted failed into db7';
                        //     }
    
                        
                        // } else {
                        // //insert the record if not exist
                        //     $insert1 = DB::table('input_datasets')
                        //     ->insert([
                        //         'order_code' => $sampleId, 'dataset' => json_encode($data)
                        //     ]);
                        //     //service calling for insert oeder code into order_code_queue table
                        //     $insert2 = ( new InsertOrderCodeService)->insertOrderCode($sampleId);
        
                        //     if($insert1 && $insert2){
                        //         (new SFTPService)->moveInFileToArchive($key);
                        //         print_r('insert hoise');
        
                        //     }else{
        
                        //         echo 'inserted failed into db8';
                        //     }

                        // }
                    }
                
                }
            return response()->json($files);  
    }
}
        catch(Exception $ex)
        {
            Log::channel('scheduler_error')->error('$$$ -> exception : ' . $ex->getMessage());
            return $ex->getMessage();
        }
}


    public function generatePDFreportAndUpdateResponse()
    {
        ini_set('max_execution_time', '0');

       // $_pdfGenerationService = new PDFGenerationService();

        try
        {
            foreach ($this->getPendingOrders() as $order) {
                /**
                 * update order code to processing with workStatus value 1
                 */
                $tableName = 'order_code_queues';
                $this->_pdfGenerationService->Log_scheduler_info('-> pdf generation started for order_Code: '. $order->orderCode); 

                // OrderCodeQueue::where("orderCode", $order->orderCode)->update(["workStatus" => 1]);
                DB::table($tableName)->where("orderCode", $order->orderCode)->update(["workStatus" => 1]);

                //ducktap : bypass // because it takes huge time.
                // if($order->orderCode == '235741-daffb2d0-2516-4c35-b16a-d65530834e27')
                // {
                //     continue;
                // }

                // For Local test // RUN_TEST_REQUEST
                // if (env('RUN_TEST_REQUEST_BYPASS') == 'yes') 
                // {
                //     // Go ahead
                //     if($order->orderCode == '235741-daffb2d0-2516-4c35-b16a-d65530834e27' || $order->orderCode == '236399-78c2de22-f509-484f-a5c6-11263fdd3b99' 
                //     || $order->orderCode == '236398-4fcb2a99-3863-4c8c-9ec5-5b3a7fab969c' || $order->orderCode == '236391-5a0e403c-4fff-4b45-9cf7-e13a478d4684')
                //     {
                //         continue;
                //     }
                // }

                // // For Local test // RUN_TEST_REQUEST
                // if (env('RUN_TEST_REQUEST') == 'yes') 
                // {
                //     // Go ahead
                // }
                // else
                // {
                //     // don't run test request
                //     if($order->orderCode == '236399-78c2de22-f509-484f-a5c6-11263fdd3b99' || $order->orderCode == '236398-4fcb2a99-3863-4c8c-9ec5-5b3a7fab969c' || $order->orderCode == '236391-5a0e403c-4fff-4b45-9cf7-e13a478d4684')
                //     {
                //         continue;
                //     }
                // }

                //Ducktap: jafar : call api directly
                 //$response = Http::post(Config::get('nih.stratus_pdf_generation_api').$order->orderCode, []);
                $requestURL = "http://127.0.0.1:8000/api/generate-report?order_code=".$order->orderCode;
                echo "Request URL: ".$requestURL;
                $response = Http::post($requestURL, []);
                
                // Handle the response as needed
                $data = $response->json();
                if(is_array($data)){
                   // Log::channel('scheduler_error')->error('$$$ -> api output  : '. implode(', ', $data)); 
                }
                else
                {
                   // Log::channel('scheduler_error')->error('$$$ -> api output  : '. $data); 
                }
               
                // You can also check for success
                $output_report = false;
                if ($response->successful()) {
                    $output_report = true;
                } else {
                    // Handle the error response
                    $statusCode = $response->status();
                    $errorData = $response->json();
                }

              // $pdf_generation_output = $_pdfGenerationService->getPDFReport_from_background_service($order->orderCode);

                if ($output_report) 
                {
                    /**
                     * update order code to processing with workStatus value 2
                     */
                    DB::table($tableName)->where("orderCode", $order->orderCode)->update(["workStatus" => 2]);
                    //OrderCodeQueue::where("orderCode", $order->orderCode)->update(["workStatus" => 2]);
                    $this->_pdfGenerationService->Log_scheduler_info('-> pdf generation successful for order_Code: '. $order->orderCode); 
                } 
                else 
                {
                    /**
                     * update order code to processing with workStatus value 0
                     * and increment the number of failure
                     */
                    DB::table($tableName)->where("orderCode", $order->orderCode)->update(["workStatus" => 0, "numberOfFailur" => $order->numberOfFailur + 1 ]);
                    //  OrderCodeQueue::where("orderCode", $order->orderCode)
                    //     ->update([
                    //         "workStatus" => 0,
                    //         "numberOfFailur" => $order->numberOfFailur + 1
                    //     ]);
                    
                        Log::channel('scheduler_error')->error('$$$ -> pdf generation failed for order_code: '. $order->orderCode); 
                        $this->_pdfGenerationService->Log_scheduler_info('-> pdf generation failed for order_Code: '. $order->orderCode); 
                }

            }
        }
        catch(Exception $ex)
        {
            $this->_pdfGenerationService->Log_scheduler_info('-> ERROR for order_Code: '. $ex->getMessage()); 
            Log::channel('scheduler_error')->error('$$$ -> pdf generation - Exception: '. $ex->getMessage()); 
            return $ex->getMessage();
        }

    }

    public function getPendingOrders()
    {
        $tableName = 'order_code_queues';
        // remove take(2) methon in between
       // return OrderCodeQueue::where('workStatus', '=', 0)->where('numberOfFailur', '<', 5)->take(1)->get();
        return DB::table($tableName)->where('workStatus', '=', 0)->where('numberOfFailur', '<', $this->_pdfGenerationService->get_env_TRY_IF_FAILED_TIMES() )->orderBy('id', 'desc')->take(1)->get();
    }
}
