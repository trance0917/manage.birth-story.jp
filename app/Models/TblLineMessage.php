<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


/**
 * Class TblLineMessage
 *
 * @property int $id
 * @property string $tbl_patient_id
 * @property string|null $message
 * @property string|null $image1_url
 * @property string|null $image2_url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class TblLineMessage extends Model
{
	protected $table = 'tbl_line_messages';

	protected $fillable = [
		'tbl_patient_id',
		'message',
		'image1_url',
		'image2_url'
	];

    public function tbl_patient()
    {
        return $this->hasOne(TblPatient::class, 'tbl_patient_id', 'tbl_patient_id');
    }


}
