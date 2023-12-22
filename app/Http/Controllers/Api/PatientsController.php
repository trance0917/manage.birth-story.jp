<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TblPatient;

use App\Services\LineBotService;
use Illuminate\Http\Request;
use App\Services\PatientService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\MessageBuilder\RawMessageBuilder;
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
    public function paymentComplete(TblPatient $tbl_patient,Request $request,PatientService $patient_service){

        DB::beginTransaction();
        try {
            $tbl_patient->payment_status=3;
            $tbl_patient->save();
            
            $mst_maternity = $tbl_patient->mst_maternity;
            
            $tbl_patient = $patient_service->getPatient($tbl_patient->tbl_patient_id);
            
            $line_bot_service = new LineBotService($mst_maternity);
            $line_bot_service->pushMessage($tbl_patient->line_user_id, new TextMessageBuilder('アマゾンギフト('.$tbl_patient->review_point.'pt)のお支払いが完了いたしました。'), $tbl_patient);
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
