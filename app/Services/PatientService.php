<?php
namespace App\Services;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use App\Models\TblPatient;
use App\Traits\SearchTableId;

class PatientService{

    use SearchTableId;
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
    public function getPatientIds(int $limit, int $offset, string $sort_key, string $sort_type, array $search_params)
    {
        //バリデーション
        $validator = Validator:: make([
            'limit' => $limit,
            'offset' => $offset,
            'sort_key' => $sort_key,
            'sort_type' => $sort_type,
            'search_params' => $search_params,
        ], [
            'limit' => 'required|integer',
            'offset' => 'required|integer',
            //ソートするカラムを追加するときはここにも入れる
            'sort_key' => ['required','string','regex:/^(tbl_patient_id|health_check_date|birth_day|updated_at|created_at)$/'],
            'sort_type' => ['required','string','regex:/^(desc|asc)$/'],

            'search_params.tbl_patients.name.like' => 'nullable|string',
            'search_params.tbl_patients.is_use_instagram.like' => 'nullable|string',

            'search_params.tbl_patients.mst_maternity_id.in.*' => 'nullable|integer',
            'search_params.tbl_patients.working_by.in.*' => 'nullable|integer',
            'search_params.tbl_patients.created_at.date_from' => 'nullable|date',
            'search_params.tbl_patients.created_at.date_to' => 'nullable|date',
            'search_params.tbl_patients.birth_day.date_from' => 'nullable|date',
            'search_params.tbl_patients.birth_day.date_to' => 'nullable|date',
            'search_params.tbl_patients.updated_at.date_from' => 'nullable|date',
            'search_params.tbl_patients.updated_at.date_to' => 'nullable|date',
            'search_params.tbl_patients.health_check_date.date_from' => 'nullable|date',
            'search_params.tbl_patients.health_check_date.date_to' => 'nullable|date',

            'search_params.tbl_patients.submitted_at.isnotnull' => 'nullable|boolean',
            'search_params.tbl_patients.undertook_at.isnotnull' => 'nullable|boolean',
            'search_params.tbl_patients.completed_at.isnotnull' => 'nullable|boolean',
            'search_params.tbl_patients.reviewed_at.isnotnull' => 'nullable|boolean',

            'search_params.tbl_patients.is_google_review.in.*' => 'nullable|integer',

            'search_params.tbl_patients.present_photoart_path.isnotnull' => 'nullable|boolean',
            'search_params.tbl_patients.present_photoart_path.isnull' => 'nullable|boolean',

            'search_params.tbl_patients.present_movie_path.isnotnull' => 'nullable|boolean',
            'search_params.tbl_patients.present_movie_path.isnull' => 'nullable|boolean',



            //mst_material_idを取得する
//            'search_params.mst_product_categories.mst_product_category_id.in.*' => 'nullable|integer',
//            'search_params.mst_material_groups.mst_material_group_id.in.*' => 'nullable|integer',
//            'search_params.mst_materials.mst_material_id.in.*' => 'nullable|integer',

//            //mst_dimensions_format_idを取得する
//            'search_params.mst_shapes.mst_shape_id.in.*' => 'nullable|integer',
//            'search_params.mst_dimensions_formats.mst_dimensions_format_id.in.*' => 'nullable|integer',
//
//            //tbl_product_composition_registration_idを取得する
//            'search_params.tbl_product_composition_registrations.tbl_product_composition_registration_id.in.*' => 'nullable|integer',
//            'search_params.tbl_product_composition_registrations.created_at.from' => 'nullable|date',
//            'search_params.tbl_product_composition_registrations.created_at.to' => 'nullable|date',
//            'search_params.tbl_product_composition_registrations.tbl_product_composition_id.in.*' => 'nullable|integer',
//            'search_params.tbl_product_composition_registrations.published_at.isnull' => 'nullable|boolean',
//            'search_params.tbl_product_composition_registration_details.name.like' => 'nullable|string',
//            'search_params.tbl_product_composition_registration_details.search_words.like' => 'nullable|string',
//            'search_params.tbl_product_registrations.mst_product_type_id.in.*' => 'nullable|integer',
//            'search_params.tbl_supplier_product_versions.is_volume_discount_settings_enabled.in.*' => 'nullable|boolean',
//            'search_params.tbl_supplier_product_versions.is_pre_order_discount_settings_enabled.in.*' => 'nullable|boolean',
//            'search_params.tbl_supplier_product_versions.tbl_supplier_id.in.*' => 'nullable|integer',
//            'search_params.tbl_supplier_product_versions.is_admin_edit.in.*' => 'nullable|boolean',
//            'search_params.tbl_supplier_product_versions.approval_at.isnull' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return [
                'count' => 0,
                'tbl_patient_ids' => [],
                'errors' => $validator->errors(),
            ];
        }
        $validated = $validator->validated();
        $search_params = [];
        if (!empty($validated['search_params'])) {
            $search_params = $validated['search_params'];
        }

        $sub_search_tables = [//検索するid => [search_paramsのkey => 検索対象のパス]
            'tbl_patient_id' => [
                'tbl_patients' => '*',
            ],
        ];

        $sub_search_result = [];
        foreach ($sub_search_tables as $search_id => $enable_params) {
            $table = null;
            switch ($search_id) {
                case 'tbl_patient_id':
                    $table = TblPatient::select([
                        $search_id,
                    ]);
                    break;
            }
            $search_table_id_result = $this->searchTableIds($table, $search_id, $enable_params, $search_params);
            if (is_array($search_table_id_result)) {
                if (empty($search_table_id_result)) {
                    return [
                        'count' => 0,
                        'tbl_patient_ids' => [],
                    ];
                }
                $sub_search_result[$search_id] = $search_table_id_result;
            }
        }

        $tbl_patients = new TblPatient;

        //他テーブルIDで絞り込み
        foreach ($sub_search_result as $sub_search_id => $sub_search_result_ids) {
            if (!empty($sub_search_result)) {
                $search_path = '';
                switch ($sub_search_id) {
                    case 'tbl_patient_id':
                        $search_path = '';
                        break;
                }
                if ($sub_search_id == 'tbl_patient_id') {
                    $tbl_patients = $tbl_patients->whereIn($sub_search_id, $sub_search_result_ids);
                } else {
                    $tbl_patients = $tbl_patients->whereHas($search_path, function ($query) use ($sub_search_id, $sub_search_result_ids) {
                        $query->whereIn($sub_search_id, $sub_search_result_ids);
                    });
                }
            }
        }

        $tbl_patient_count = $tbl_patients->count();

        $tbl_patient_id_array = [];

        $tbl_patient_id_ids = $tbl_patients
            ->orderByRaw(''.$sort_key.' IS NULL ASC')
            ->orderBy($sort_key,$sort_type)
            ->offset($validated['offset'])
            ->limit($validated['limit'])
            ->get();

        if (!empty($tbl_patient_id_ids->count())) {
            $tbl_patient_id_array = $tbl_patient_id_ids->pluck('tbl_patient_id')->toArray();
        }

        return [
            'count' => $tbl_patient_count,
            'tbl_patient_ids' => $tbl_patient_id_array,
        ];

    }
    public function getPatient(int $tbl_patient_id){
        $validator = Validator:: make(['tbl_patient_id' => $tbl_patient_id], [
            'tbl_patient_id' => 'required|integer|exists:tbl_patients,tbl_patient_id,deleted_at,NULL',
        ]);

        if ($validator->fails()) {
            return [];
        }

        $tbl_patient = TblPatient::with([
            'tbl_patient_mediums'=>function ($query){
                //ファイル名 空にしているのはミューテタで取得できるため
                $query->select(['tbl_patient_id','extension','tbl_patient_medium_id','type','file_name','order','registered_at'])->selectRaw('\'\' AS `src`');
            },
            'tbl_patient_mediums.tbl_patient:tbl_patient_id,code',
            'tbl_patient_reviews'=>function ($query){
                //ファイル名 空にしているのはミューテタで取得できるため
                $query->select(['tbl_patient_review_id','tbl_patient_id','mst_maternity_question_id','score']);
            },
            'mst_maternity:mst_maternity_id,name',
            'tbl_user_working_by:tbl_user_id,name',
            'tbl_user_task_retouch_by:tbl_user_id,name',
            'tbl_user_payment_by:tbl_user_id,name',
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
            'reviewed_at',
            'is_google_review',
            'review_point',
            'amazon_id',
            'payment_status',
            'payment_by',
            'undertook_at',
            'working_by',
            'task_retouch_by',
            'present_movie_path',
            'present_photoart_path',
            'present_after_notified_at',
            'is_present_after_notified',
            'completed_at',
            'presented_at',
            'memo',
            'created_at',
            'updated_at',
        )
            ->selectRaw('\'\' AS `old_working_by`')
            ->selectRaw('\'\' AS `average_score`')
            ->find($tbl_patient_id);

        return $tbl_patient;
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
            'tbl_patient_mediums'=>function ($query){
                //ファイル名 空にしているのはミューテタで取得できるため
                $query->select(['tbl_patient_id','extension','tbl_patient_medium_id','type','file_name','order','registered_at'])->selectRaw('\'\' AS `src`');
            },
            'tbl_patient_mediums.tbl_patient:tbl_patient_id,code',
            'tbl_patient_reviews'=>function ($query){
                //ファイル名 空にしているのはミューテタで取得できるため
                $query->select(['tbl_patient_review_id','tbl_patient_id','mst_maternity_question_id','score']);
            },
            'mst_maternity:mst_maternity_id,name',
            'tbl_user_working_by:tbl_user_id,name',
            'tbl_user_task_retouch_by:tbl_user_id,name',
            'tbl_user_payment_by:tbl_user_id,name',
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
            'reviewed_at',
            'is_google_review',
            'review_point',
            'amazon_id',
            'payment_status',
            'payment_by',
            'undertook_at',
            'working_by',
            'task_retouch_by',
            'present_movie_path',
            'present_photoart_path',
            'present_after_notified_at',
            'is_present_after_notified',
            'completed_at',
            'presented_at',
            'memo',
            'created_at',
            'updated_at',
        )
            ->selectRaw('\'\' AS `old_working_by`')
            ->selectRaw('\'\' AS `average_score`')
            ->whereIn('tbl_patient_id', $tbl_patient_ids)
            ->orderByRaw('FIELD(tbl_patient_id,'.implode(',',$tbl_patient_ids).') ')
            ->get();


        return $tbl_patients;
    }
}
