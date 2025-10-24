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
 * @property string $company
 * @property string $warehouse
 * @property string $lot_number
 * @property string $serial_number
 * @property string $user
 * @property Carbon $date_counted
 * @property Carbon $time_counted
 * @property float $expected_qty
 * @property float $standard_cost
 * @property float $cost_counted
 * @property float $plus_minus
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class NoTagPartHouston extends Model
{
    protected $table = 'no_tag_parts_houston';

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
        'company',
        'warehouse',
        'lot_number',
        'serial_number',
        'user',
        'date_counted',
        'time_counted',
        'standard_cost',
        'cost_counted',
    ];
}
