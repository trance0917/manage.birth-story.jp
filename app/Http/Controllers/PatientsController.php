<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\PatientService;
use Illuminate\Pagination\LengthAwarePaginator;

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
        $search_params['per'] = $search_params['per'] ?? 20;

        $data = $patient_service->getPatientIds(
            $search_params['per']
            ,($search_params['page'] - 1) * $search_params['per']
            ,$search_params
        );

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
        return view('patiens.index' ,compact('search_params','list'));
    }
    public function json(Request $request, PatientService $patient_service)
    {


        $d = $patient_service->getPatients([16,25,26,27,28,30,37,51,52,53,54,55,57]);
        return response()->json($d);
    }
}
