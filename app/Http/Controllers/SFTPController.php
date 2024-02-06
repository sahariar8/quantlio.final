<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SFTPService;
use App\Services\PDFGenerationService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Sabberworm\CSS\Value\URL;
// use Barryvdh\DomPDF\Facade as PDF;


class SFTPController extends Controller
{
    /**
     * @throws FileNotFoundException
     */
    // public function listAllFiles(): JsonResponse
    // {
    //     $files = (new SFTPService)->listAllInFiles();

    //     foreach ($files as $key=>$file) {
    //         $data = json_decode($file, true);
            
    //         if ($data !== null) {
    //             // Access the sampleid
    //             $sampleId = $data['sampleid'];
    //             echo "Sample ID: $sampleId<br>";

    //             $existingRecord = DB::table('input_datasets')
    //                 ->where('order_code', $sampleId)
    //                 ->first();
    
    //             if ($existingRecord) {
    //             // Update the existing record
    //                 print_r('update');
    //                 $update = DB::table('input_datasets')
    //                     ->where('order_code', $sampleId)
    //                     ->update([
    //                         'dataset' => json_encode($data)
    //                     ]);
                
    //             } else {
    //             //insert the record if not exist
    //                 $insert = DB::table('input_datasets')
    //                 ->insert([
    //                     'order_code' => $sampleId, 'dataset' => json_encode($data)
    //                 ]);

    //                 if($insert){

    //                     (new SFTPService)->moveInFileToArchive($key);

    //                 }else{

    //                     echo 'inserted failed into db';
    //                 }
    //             }
    //         } else {
    //             // Handle JSON decoding error
    //             echo "Failed to decode JSON data";
    //         }
    //     }
    //     return response()->json($files);
    // }


    // public function moveFileToArchive(Request $request): JsonResponse
    // {
    //     $filename = $request->query('fileName');

    //     if ($filename) {
    //         $status = (new SFTPService)->moveInFileToArchive($filename);

    //         if ($status) {
    //             return response()->json([
    //                 'message' => 'File operations completed successfully'
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'message' => 'File not found or unable to move'
    //             ], 404);
    //         }
    //     } else {
    //         return response()->json([
    //             'message' => 'Filename parameter is missing'
    //         ], 400);
    //     }
    // }

    public function createTestOutput(Request $request): JsonResponse
    {
        $request->validate([
            'fileName' => 'required|string',
            'fileContent' => 'required|array',
        ]);

        $fileName = $request->input('fileName');
        $fileContent = $request->input('fileContent');

        $jsonContent = json_encode($fileContent, JSON_PRETTY_PRINT);

        //check
        // $pdf = PDF::loadHTML('<pre>' . htmlspecialchars($jsonContent) . '</pre>');
        
        // // Save or download the PDF
        // $pdf->save(storage_path("app/pdf/{$fileName}.pdf"));

        // return response()->json(['message' => 'PDF created successfully']);
    
        $pdfgenerate = (new PDFGenerationService)->test_generatePDF_from_mock_output();
        dd($pdfgenerate);


        //end
        // $status = (new SFTPService)->createFileInJsonOut($fileName, $jsonContent);

        // if ($status) {
        //     return response()->json([
        //         'message' => 'File created successfully'
        //     ]);
        // }

        // return response()->json([
        //     'message' => 'Failed to create file'
        // ], 500);
    }
}