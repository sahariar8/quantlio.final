<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GetDDIController;
use App\Http\Controllers\GetConditionController;
use App\Http\Controllers\ContraindicationsAPI;
use App\Http\Controllers\ContraindicationsAPINew;
use App\Http\Controllers\GeneratePDFReportController;
use App\Http\Controllers\SchedulerJobController;
use App\Http\Controllers\MockRequestApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
//Route::POST("getData",[GetDDI::class,'call_api']);


// Route::get( '/user/profile',
//     [UserProfileController::class, 'show']
// )->name('profile');

// });

Route::group(['middleware' => ['auth:sanctum']],function() {
    Route::GET('/getDDI',[GetDDIController::class, 'getInteractions']);
    Route::GET('/getCondition',[GetConditionController::class, 'getCondition']);
    Route::POST('/getContraindications',[ContraindicationsAPI::class, 'getInteractions']);
    Route::POST('/getContraindicationsNew',[ContraindicationsAPINew::class, 'getInteractions']);
    // Route::POST('/getResult',[GeneratePDFReportController::class, 'getPDFReport']);

    // Route::POST('/getContraindications',[GeneratePDFReportController::class, 'getPDFReport']);
    Route::get('/refresh', [ContraindicationsAPI::class, 'refresh']);

    //Deployed for client
    Route::POST('/generate-report', [GeneratePDFReportController::class, 'getPDFReport_from_api']);
});

Route::POST('/token-auth',[ContraindicationsAPI::class, 'login']);
Route::POST('/register',[ContraindicationsAPI::class, 'register']);
Route::GET('/getMeshCode',[GetDDIController::class, 'getMeshValue']);

//Deployed for client
Route::POST('/generate-report', [GeneratePDFReportController::class, 'getPDFReport_from_api']);

// Mock Request Queue and Get single data_set
//get failed-order-code and failed-dataset
Route::GET('/request_queue', [MockRequestApiController::class, 'order_code']);
Route::GET('/order_code_dataSet/{order_code}', [MockRequestApiController::class, 'dataSet']);

//sFTP
Route::get('sftp/list', [SFTPController::class, 'listAllFiles']);
Route::get('sftp/move', [SFTPController::class, 'moveFileToArchive']);
Route::post('sftp/out', [SFTPController::class, 'createTestOutput']);