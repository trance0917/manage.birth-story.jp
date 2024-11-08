<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\SerializeDate;

/**
 * Class TblPatient
 *
 * @property int $tbl_patient_id
 * @property int $mst_maternity_id
 * @property string $code
 * @property string $line_name
 * @property string $line_user_id
 * @property string $richmenu_id
 * @property string|null $name
 * @property string|null $roman_alphabet
 * @property string|null $baby_name
 * @property string|null $baby_roman_alphabet
 * @property Carbon|null $birth_day
 * @property Carbon|null $birth_time
 * @property int|null $weight
 * @property float|null $height
 * @property int|null $sex
 * @property int|null $what_number
 * @property Carbon|null $health_check_date
 * @property string|null $message
 * @property int|null $is_use_instagram
 * @property string|null $review
 * @property string|null $amazon_id
 * @property Carbon|null $completed_at
 * @property Carbon|null $undertook_at
 * @property int $working_by
 * @property string|null $memo
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class TblPatient extends Model
{
	use SoftDeletes;
    use SerializeDate;
	protected $table = 'tbl_patients';
	protected $primaryKey = 'tbl_patient_id';

	protected $casts = [
		'mst_maternity_id' => 'int',
		'birth_day' => 'datetime:Y-m-d',
		'birth_time' => 'datetime:H:i',
		'weight' => 'int',
		'height' => 'float',
		'sex' => 'int',
		'what_number' => 'int',
		'health_check_date' => 'datetime:Y-m-d',
		'is_use_instagram' => 'int',
        'reviewed_at' => 'datetime',
        'is_google_review' => 'int',
        'review_point' => 'int',
        'submitted_at' => 'datetime',
		'completed_at' => 'datetime',
		'undertook_at' => 'datetime',
        'presented_at' => 'datetime',
        'is_present_after_notified' => 'int',
        'is_blocked' => 'int',
        'present_after_notified_at' => 'datetime',
		'working_by' => 'int'
	];

	protected $fillable = [
		'mst_maternity_id',
		'code',
		'line_name',
		'line_user_id',
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
		'review',
        'reviewed_at',
        'is_google_review',
		'amazon_id',
        'payment_status',
        'payment_by',
		'completed_at',
		'undertook_at',
		'working_by',
        'presented_at',
        'is_present_after_notified',
        'present_after_notified_at',
        'is_blocked',
		'memo'
	];

    public static $sex_types = [
        1=>'男の子',
        2=>'女の子',
    ];

    public function mst_maternity()
    {
        return $this->hasOne(MstMaternity::class, 'mst_maternity_id', 'mst_maternity_id');
    }
    public function tbl_user_working_by()
    {
        return $this->hasOne(TblUser::class, 'tbl_user_id', 'working_by');
    }
    public function tbl_user_task_retouch_by()
    {
        return $this->hasOne(TblUser::class, 'tbl_user_id', 'task_retouch_by');
    }
    public function tbl_user_payment_by()
    {
        return $this->hasOne(TblUser::class, 'tbl_user_id', 'payment_by');
    }
    public function tbl_patient_reviews()
    {
        return $this->hasMany(TblPatientReview::class, 'tbl_patient_id', 'tbl_patient_id');
    }
    public function tbl_patient_mediums()
    {
        return $this->hasMany(TblPatientMedium::class, 'tbl_patient_id', 'tbl_patient_id');
    }
    public function log_line_message()
    {
        return $this->hasMany(LogLineMessage::class, 'tbl_patient_id', 'tbl_patient_id')
            ->select(['log_line_message_id','tbl_patient_id','message','http_status','created_at'])
            ->orderBy('created_at', 'desc');
    }
    public function getAverageScoreAttribute()
    {
        return sprintf("%.1f",round($this->tbl_patient_reviews->average('score'),1));
    }
    public function getOldWorkingByAttribute()
    {
        return $this->working_by;
    }
    public function getPresentMoviePathUrlAttribute()
    {
        return config('app.url').'/storage/patients/'.$this->tbl_patient_id.'_'.$this->code.'/'.$this->present_movie_path;
    }
    public function getPresentPhotoartPathUrlAttribute()
    {
        return config('app.url').'/storage/patients/'.$this->tbl_patient_id.'_'.$this->code.'/'.$this->present_photoart_path;
    }
}
