<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Inventory
 *
 * @property int $id
 * @property string $tag
 * @property bool $tagStatus
 * @property string $part
 * @property string $partDescription
 * @property string $bin
 * @property string $warehouse
 * @property string $binDescription
 * @property string $lot
 * @property string $serial
 * @property float $count
 * @property bool $byWeight
 * @property string $uom
 * @property int $user
 * @property Carbon $dateCounted
 * @property Carbon $timeCounted
 * @property string $note
 * @property double $expectedQty
 * @property float $standardCost
 * @property float $costCounted
 * @property float $costExpected
 * @property float plus_minus
 * @property string $top_eight
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property bool $counted
 *
 * @package App\Models
 */
class Inventory extends Model
{
	protected $table = 'inventory';

	protected $casts = [
		'count' => 'float',
		'date_counted' => 'date',
		'time_counted' => 'time',
	];

	protected $fillable = [
		'tag',
		'part',
		'part_description',
		'bin',
		'description',
		'lot_number',
		'serial_number',
		'count',
		'by_weight',
		'uom',
		'activity_before_count',
		'returned',
		'user',
		'date_counted',
		'time_counted',
		'note',
		'has_transactions',
		'sheet_number',
		'tag_status',
		'enable_uom_worksheet',
		'period_end_date',
		'period_start_date',
		'cycle_period',
		'company',
		'warehouse',
		'expected_qty',
		'standard_cost'
	];

}
