<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MstMaternityQuestion
 *
 * @property int $mst_maternity_question_id
 * @property int $mst_maternity_id
 * @property string $question
 * @property int $order
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class MstMaternityQuestion extends Model
{
	use SoftDeletes;
	protected $table = 'mst_maternity_questions';
	protected $primaryKey = 'mst_maternity_question_id';

    protected $casts = [
		'mst_maternity_id' => 'int',
		'order' => 'int'
	];

	protected $fillable = [
		'mst_maternity_id',
		'question',
		'order'
	];
}
