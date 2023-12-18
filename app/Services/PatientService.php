<?php
namespace App\Services;

use Illuminate\Support\Facades\Validator;
use App\Models\TblPatient;

class PatientService{


    /*
     * 検索条件から件数商品件数を返す
     * limit・offsetを考慮して該当する商品IDも返す
     * @param int $limit
     * @param int $offset
     * @param array $search_params 検索パラメータ。バリデーションにかかれている形が許可されている
     * search_params[tbl_name][column_name][in] = [1,2,3,]
     * search_params[tbl_name][column_name][from] = 1
     * search_params[tbl_name][column_name][to] = 3
     * search_params[tbl_name][column_name][like] = 'test'
     * search_params[tbl_name][column_name][isnull] = 1
     * @return array [count => 件数, product_composition_registration_ids => IDの配列]
     */
    public function getPatientIds(int $limit, int $offset, array $search_params)
    {
        return [
            'count' => 100,
            'tbl_patient_ids' => [51,52,53,54,55,57],
        ];

    }
    public function getPatients(array $tbl_patient_ids)
    {
        //バリデーション
        $validator = Validator:: make(['tbl_patient_ids' => $tbl_patient_ids], [
            'tbl_patient_ids' => 'required|array',
            'tbl_patient_ids.*' => 'integer',
        ]);
        if ($validator->fails()) {
            return [];
        }

        $tbl_patients = TblPatient::with([
            'tbl_patient_mediums:tbl_patient_medium_id,tbl_patient_id,type,file_name,registered_at,extension,order',
            'tbl_patient_reviews:tbl_patient_review_id,tbl_patient_id,mst_maternity_question_id,score',
            'mst_maternity:mst_maternity_id,name',
        ])->select(
    'tbl_patient_id',
            'mst_maternity_id',
            'code',
            'line_name',
            'line_user_id',
            'line_picture_url',
            'richmenu_id',
            'name',
            'roman_alphabet',
            'baby_name',
            'baby_roman_alphabet',
            'birth_day',
            'birth_time',
            'weight',
            'height',
            'sex',
            'what_number',
            'health_check_date',
            'message',
            'is_use_instagram',
            'submitted_at',
            'review',
            'review_point',
            'amazon_id',
            'payment_status',
            'undertook_at',
            'undertook_by',
            'completed_at',
            'presented_at',
            'memo'
        )->whereIn('tbl_patient_id', $tbl_patient_ids)->get();

        return $tbl_patients;
    }
}
