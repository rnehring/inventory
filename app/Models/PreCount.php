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
 * @property string $tag
 * @property bool $tag_status
 * @property string $part
 * @property string $part_description
 * @property string $warehouse
 * @property string $bin
 * @property string $bin_description
 * @property bool $bin_verified
 * @property string $verified_date
 * @property float $count
 * @property bool $by_weight
 * @property string $uom
 * @property string $lot_number
 * @property string $serial_number
 * @property int $user
 * @property double $expected_qty
 * @property float $standard_cost
 * @property float $cost_counted
 * @property float $cost_expected
 * @property Carbon $date_counted
 * @property Carbon $time_counted
 * @property float plus_minus
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class PreCount extends Model
{
	protected $table = 'inventory_precount';
	public $timestamps = false;

	protected $casts = [
		'bin_verified' => 'bool',
		'verified_date' => 'datetime'
	];

	protected $fillable = [
		'tag',
        'tag_status',
		'part',
        'part_description',
        'warehouse',
		'bin',
        'bin_description',
		'bin_verified',
		'verified_date',
		'count',
        'by_weight',
		'uom',
		'lot',
		'serial',
		'user',
		'expected_qty',
		'standard_cost',
		'cost_counted',
        'cost_expected',
        'date_counted',
        'time_counted',
        'plus_minus'
	];
}
