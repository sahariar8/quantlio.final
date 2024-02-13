<?php

namespace App\Services;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SFTPService
{
      protected PDFGenerationService  $_pdfGenerationService;

      function __construct()
     {
        $this->_pdfGenerationService = new PDFGenerationService();
     }

    public function getDataSetFrom_sFTPserver(): JsonResponse
    {
        try{
            $files = $this->getListAllInFilesInsFTPServer();
            // dd($files);

            $this->_pdfGenerationService->Log_scheduler_info('-> Get list Content of files '. json_encode($files)); 

            foreach ($files as $key=>$file) {

                $data = json_decode($file, true);
                if ($data !== null) {
                    // Access the sampleid
                    $sampleId = $data['sampleid'];
                    echo "Sample ID: $sampleId<br>";

                    $existingRecord = DB::table('input_datasets')
                        ->where('order_code', $sampleId)
                        ->first();

                        //ducktap: only insert , no update

                    // if ($existingRecord) {
                    // // Update the existing record
                    //     print_r('update');
                    //     $update = DB::table('input_datasets')
                    //         ->where('order_code', $sampleId)
                    //         ->update([
                    //             'dataset' => json_encode($data)
                    //         ]);
                    
                    // } else {
                    // //insert the record if not exist
                    //     $insert = DB::table('input_datasets')
                    //     ->insert([
                    //         'order_code' => $sampleId, 'dataset' => json_encode($data)
                    //     ]);

                    //     if($insert){

                    //         (new SFTPService)->moveInFileToArchive($key);

                    //     }else{

                    //         echo 'inserted failed into db9';
                    //     }
                    // }

                    $insert = DB::table('input_datasets')
                        ->insert([
                            'order_code' => $sampleId, 'dataset' => json_encode($data)
                        ]);

                        if($insert){

                            (new SFTPService)->moveInFileToArchive($key);

                        }else{

                            echo 'inserted failed into db9';
                        }
                } else {
                    // Handle JSON decoding error
                    echo "Failed to decode JSON data";
                }
            }
            return response()->json($files);
        }
        catch(\Exception  $ex)
        {
             $this->_pdfGenerationService->Log_scheduler_info('$$$ -> Exception:: file content entry to DB'. $ex->getMessage()); 
        }
        
    }

    /**
     * @throws FileNotFoundException
     */
    public function getListAllInFilesInsFTPServer(): array
    {
        $this->_pdfGenerationService->Log_scheduler_info('-> Get list of files from server '); 

        $allFiles = Storage::disk('sftp')->allFiles('JSONOut/');
        // dd($allFiles);
        $this->_pdfGenerationService->Log_scheduler_info('-> Get list of files :: '.  count($allFiles)); 


        $filteredFiles = array_filter($allFiles, function ($filePath) {
            return !Str::startsWith($filePath, 'JSONOut/Archive/');
        });
        // dd($filteredFiles);


        $filesWithContent = [];
        foreach ($filteredFiles as $filePath) {
            // dd($filePath);
            $fileContent = Storage::disk('sftp')->get($filePath);
            $key = basename($filePath, '.json');
            $filesWithContent[$key] = $fileContent;
            // dd($filesWithContent,'sa');
           
        }
        // dd($filesWithContent,'sa');
         $this->_pdfGenerationService->Log_scheduler_info('-> Return file content :: '. count($filesWithContent)); 
         return $filesWithContent;


    }

    public function moveInFileToArchive($filename): JsonResponse
    {
        // $filename = $request->query('fileName');
        // dd($filename);

        if ($filename) {
            $status = $this->moveSuccessfullyGeneratedFileToArchive($filename);

            if ($status) {
                return response()->json(['message' => 'File moved to archive successfully']);
            } else {
                return response()->json([
                    'message' => 'File not found or unable to move'
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'Filename parameter is missing'
            ], 400);
        }
    }

    public function moveSuccessfullyGeneratedFileToArchive($filename): bool
    {
        $filenameWithExtension = $filename . '.json';

        $sourcePath = 'JSONOut/' . $filenameWithExtension;
        $destinationPath = 'JSONOut/Archive/' . $filenameWithExtension;

        if (Storage::disk('sftp')->exists($sourcePath)) {
            Storage::disk('sftp')->move($sourcePath, $destinationPath);
            return true;
        } else {
            return false;
        }
    }

    public function createFileInJsonOut($fileName, $fileContent): bool
    {
        if (empty($fileName)) {
            return false;
        }
        // $destinationPath = 'JSONOut/' . $fileName . '.json';
        $destinationPath = 'JSONIn/' . $fileName;
        return Storage::disk('sftp')->put($destinationPath, $fileContent);
    }

}