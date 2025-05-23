<?php

namespace App\Http\Controllers;

use App\Services\LineBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\PatientService;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\TblPatient;

class PatientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, PatientService $patient_service)
    {
        $search_params = $request->all();

        $search_params['page'] = $search_params['page'] ?? 1;
        $search_params['per'] = $search_params['per'] ?? 50;
        $search_params['sort_key'] = $search_params['sort_key'] ?? 'tbl_patient_id';
        $search_params['sort_type'] = $search_params['sort_type'] ?? 'desc';

        $data = $patient_service->getPatientIds(
            $search_params['per']
            ,($search_params['page'] - 1) * $search_params['per']
            ,$search_params['sort_key']
            ,$search_params['sort_type']
            ,$search_params
        );
//        dump($data);
//        dump(\App\Models\MstMaternity::get());

        $count = $data['count'];
        $tbl_patient_ids = $data['tbl_patient_ids'];

        $products = $patient_service->getPatients($tbl_patient_ids);
        $list = new LengthAwarePaginator(
            $products
            ,$count
            ,$search_params['per']
            ,$search_params['page']
            ,['path' => $request->url()]
        );
        $list->onEachSide(5);
        return view('patients.index' ,compact('search_params','list'));
    }
    public function richmenuImg(TblPatient $tbl_patient,Request $request, PatientService $patient_service){
        $line_bot_service = new LineBotService();
        header('Content-Type: image/jpeg');
        echo $line_bot_service->downloadRichMenuImage($tbl_patient->richmenu_id)->getRawBody();
    }

    public function lineLog(TblPatient $tbl_patient,Request $request, PatientService $patient_service){
        return response()->json($tbl_patient->log_line_message);
    }

    public function json(Request $request, PatientService $patient_service)
    {
        $d = $patient_service->getPatient(60);
        return response()->json($d);
    }

    public function edit(TblPatient $tbl_patient,Request $request, PatientService $patient_service)
    {
        $line_bot_service = new LineBotService();

        $richmenu = $line_bot_service->getRichMenuId($tbl_patient->line_user_id);
        $richmenu_id = '';
        if($richmenu->getHTTPStatus()=='200'){
            $richmenu_id = $richmenu->getJSONDecodedBody()['richMenuId'];
        }

//        $line_bot_service->pushMessageFollow($tbl_patient);
        $tbl_patient = $patient_service->getPatient($tbl_patient->tbl_patient_id);

        return view('patients.edit' ,compact('tbl_patient','richmenu_id'));
    }

}
