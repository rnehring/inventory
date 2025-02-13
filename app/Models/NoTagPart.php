<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NoTagPart
 * 
 * @property int $id
 * @property string $part
 * @property string $bin
 * @property float $count
 * @property string $uom
 * @property int $by_weight
 * @property Carbon $date_counted
 * @property string $company
 * @property string $plant
 * @property string $lot_number
 * @property string $serial_number
 * @property string $user
 * @property float $expected_qty
 * @property float $standard_cost
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class NoTagPart extends Model
{
	protected $table = 'no_tag_parts';

	protected $casts = [
		'count' => 'float',
		'by_weight' => 'int',
		'date_counted' => 'datetime',
		'expected_qty' => 'float',
		'standard_cost' => 'float'
	];

	protected $fillable = [
		'part',
		'bin',
		'count',
		'uom',
		'by_weight',
		'date_counted',
		'company',
		'plant',
		'lot_number',
		'serial_number',
		'user',
		'expected_qty',
		'standard_cost'
	];
}
