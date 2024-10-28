<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TblPatient;

use App\Services\LineBotService;
use Illuminate\Http\Request;
use App\Services\PatientService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use LINE\LINEBot\MessageBuilder\RawMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class PatientsController extends Controller
{


    public function workBegin(TblPatient $tbl_patient,Request $request,PatientService $patient_service){

        DB::beginTransaction();
        try {
            $tbl_patient->undertook_at=now();
            $tbl_patient->working_by=\Auth::user()->tbl_user_id;
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
            $tbl_patient->working_by=\Auth::user()->tbl_user_id;
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
            $tbl_patient->payment_by=\Auth::user()->tbl_user_id;
            $tbl_patient->save();
            $mst_maternity = $tbl_patient->mst_maternity;
            $tbl_patient = $patient_service->getPatient($tbl_patient->tbl_patient_id);
            $line_bot_service = new LineBotService();
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

    public function changeWorkingBy(TblPatient $tbl_patient,Request $request,PatientService $patient_service){

        DB::beginTransaction();
        try {
            $tbl_patient->working_by=$request->working_by;
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


    public function taskRetouchByComplete(TblPatient $tbl_patient,Request $request,PatientService $patient_service){
        DB::beginTransaction();
        try {
            $tbl_patient->task_retouch_by=\Auth::user()->tbl_user_id;
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

    public function changeDeletedAt(TblPatient $tbl_patient,Request $request,PatientService $patient_service){
        DB::beginTransaction();
        try {
            $tbl_patient->delete();
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
    public function changeIsGoogleReview(TblPatient $tbl_patient,Request $request,PatientService $patient_service){
        DB::beginTransaction();
        try {
            if($request->is_google_review){
                $tbl_patient->is_google_review=0;
            }else{
                $tbl_patient->is_google_review=1;
            }
            $tbl_patient->save();

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

    public function sendLine(TblPatient $tbl_patient,Request $request,LineBotService $line_bot_service){
        $res = $line_bot_service->pushMessage($tbl_patient->line_user_id, new TextMessageBuilder($request->line_text), $tbl_patient);
        return response()->json([],$res->getHTTPStatus());
    }

    public function saveMemo(TblPatient $tbl_patient,Request $request){
        $tbl_patient->memo = $request->memo;
        $tbl_patient->save();
    }


    public function googleReviewRemind(TblPatient $tbl_patient,Request $request,LineBotService $line_bot_service){
        $line_bot_service->pushMessagePresentHighScoreReview($tbl_patient);


//        $res = $line_bot_service->pushMessage($tbl_patient->line_user_id, new TextMessageBuilder($request->line_text), $tbl_patient);
    }


    public function savePresent(TblPatient $tbl_patient,Request $request,PatientService $patient_service){
        $key = $request->key;

        if($key=='present_movie_path'){
            $filesize=1024*400;
            $validator = Validator:: make(['file' => $request->file,], ['file' => 'file|max:'.$filesize.'|mimes:mp4',]);
        }else{
            $filesize=1024*2;
            $validator = Validator:: make(['file' => $request->file,], ['file' => 'file|max:'.$filesize.'|mimes:jpg,png',]);
        }

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'messages' => '',
                'errors' => $validator->errors(),
            ],400);
        }

        DB::beginTransaction();
        try {
            $file = $request->file;
            if ($file instanceof UploadedFile) {
                $directory_path = 'public/patients/'.$tbl_patient->tbl_patient_id.'_'.$tbl_patient->code;
                if(!\Storage::exists($directory_path)){
                    \Storage::makeDirectory($directory_path);
                }
                $filepath = pathinfo($file->getClientOriginalName());
                $filename = preg_replace('/[^0-9]/' ,'' , microtime()) . '.' . $filepath['extension'];

                $file->storeAs($directory_path, $filename);

                $old_file_name = $tbl_patient->$key;
                //古いファイルは消しておく
                if($old_file_name){
                    \Storage::disk('local')->delete(''.$directory_path .'/'. $old_file_name);
                }
                $tbl_patient->$key=$filename;
                $tbl_patient->save();
                $tbl_patient = $patient_service->getPatient($tbl_patient->tbl_patient_id);
            }
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
