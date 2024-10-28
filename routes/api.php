<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PatientsController;

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

Route::prefix('v1/g')->group(function () {
    Route::middleware('auth:api')->group(function () { //api_tokenが必要
        Route::post('/patient/{tbl_patient}/work_begin', [PatientsController::class, 'workBegin']);
        Route::post('/patient/{tbl_patient}/work_complete', [PatientsController::class, 'workComplete']);
        Route::post('/patient/{tbl_patient}/payment_complete', [PatientsController::class, 'paymentComplete']);
        Route::post('/patient/{tbl_patient}/change_working_by', [PatientsController::class, 'changeWorkingBy']);
        Route::post('/patient/{tbl_patient}/task_retouch_by_complete', [PatientsController::class, 'taskRetouchByComplete']);
        Route::post('/patient/{tbl_patient}/change_deleted_at', [PatientsController::class, 'changeDeletedAt']);
        Route::post('/patient/{tbl_patient}/change_is_google_review', [PatientsController::class, 'changeIsGoogleReview']);
        Route::post('/patient/{tbl_patient}/send_line', [PatientsController::class, 'sendLine']);
        Route::post('/patient/{tbl_patient}/save_memo', [PatientsController::class, 'saveMemo']);

//        Route::post('/patient/{tbl_patient}/google_review_remind', [PatientsController::class, 'googleReviewRemind']);



        Route::post('/patient/{tbl_patient}/save_present', [PatientsController::class, 'savePresent']);
    });
});
