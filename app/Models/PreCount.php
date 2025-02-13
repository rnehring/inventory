<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PreCount
 * 
 * @property int $id
 * @property string $epicor_sys_id
 * @property string $part_num
 * @property string $bin
 * @property string $count
 * @property string $lot
 * @property string $description
 * @property string $serial
 * @property string $company
 * @property string $uom
 * @property string $onhand
 * @property bool $bin_verified
 * @property string $user
 * @property Carbon $verified_date
 *
 * @package App\Models
 */
class PreCount extends Model
{
	protected $table = 'pre_count';
	public $timestamps = false;

	protected $casts = [
		'bin_verified' => 'bool',
		'verified_date' => 'datetime'
	];

	protected $fillable = [
		'epicor_sys_id',
		'part_num',
		'bin',
		'count',
		'lot',
		'description',
		'serial',
		'company',
		'uom',
		'onhand',
		'bin_verified',
		'user',
		'verified_date'
	];
}
