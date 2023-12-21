<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TblPatient;

use App\Services\MaternityLineBotService;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Services\PatientService;
use App\Services\MaternityService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class PatientsController extends Controller
{


    public function workBegin(TblPatient $tbl_patient,Request $request,PatientService $patient_service){

        DB::beginTransaction();
        try {
            $tbl_patient->undertook_at=now();
            $tbl_patient->undertook_by=\Auth::user()->id;
            $tbl_patient->save();
            $tbl_patient = $patient_service->getPatient($tbl_patient->tbl_patient_id);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            Log::error($e);
            return [
                'result' => false,
                'messages' => $e->getMessage(),
                'errors' => [],
            ];
        }

        return response()->json([
            'result' => $tbl_patient,
            'messages' => '',
            'errors' => [],
        ]);
    }

    public function workComplete(TblPatient $tbl_patient,Request $request,PatientService $patient_service){

        DB::beginTransaction();
        try {
            $tbl_patient->completed_at=now();
            $tbl_patient->save();
            $tbl_patient = $patient_service->getPatient($tbl_patient->tbl_patient_id);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            Log::error($e);
            return [
                'result' => false,
                'messages' => $e->getMessage(),
                'errors' => [],
            ];
        }

        return response()->json([
            'result' => $tbl_patient,
            'messages' => '',
            'errors' => [],
        ]);
    }
}
