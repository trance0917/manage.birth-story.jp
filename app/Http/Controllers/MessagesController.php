<?php

namespace App\Http\Controllers;

use App\Services\LineBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Services\PatientService;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\TblPatient;
use App\Models\TblLineMessage;


class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, PatientService $patient_service)
    {
        return view('messages.index' );
    }

    public function regist(Request $request, PatientService $patient_service)
    {
        $request->validate([
            'send_image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'send_image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'message' => 'nullable|string|max:1000',
        ]);

        // 画像保存処理
        $image1_path = null;
        $image2_path = null;

        if(!$request->hasFile('send_image_1') && !$request->hasFile('send_image_2') && !$request->input('message')){
            return redirect('/messages')->with('error', '何か入力してください');
        }

        if ($request->hasFile('send_image_1')) {
            $image1_path = $request->file('send_image_1')->store('public/messages');
        }

        if ($request->hasFile('send_image_2')) {
            $image2_path = $request->file('send_image_2')->store('public/messages');
        }
        $tbl_patients = TblPatient::where('mst_maternity_id',$request->mst_maternity_id)->get();

        // データベース保存処理
        foreach($tbl_patients AS $tbl_patient){
            if($tbl_patient->tbl_patient_id!=579){
                continue;
            }
            $line_message = new TblLineMessage();
            $line_message->tbl_patient_id = $tbl_patient->tbl_patient_id;
            $line_message->image1_url = $image1_path ? Storage::url($image1_path) : null;
            $line_message->image2_url = $image2_path ? Storage::url($image2_path) : null;
            $line_message->message = $request->input('message');
            $line_message->save();
        }


        // 成功レスポンス
        return redirect('/messages')->with('success', '順次メッセージが送信されます');

    }
}
