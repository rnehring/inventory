<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InventoryUploadPrecount
 *
 * @property int $id
 * @property string $tag
 * @property bool $tagStatus
 * @property string $part
 * @property string $partDescription
 * @property string $warehouse
 * @property string $bin
 * @property string $binDescription
 * @property bool $binVerified
 * @property string $verifiedDate
 * @property float $count
 * @property bool $byWeight
 * @property string $uom
 * @property string $lot
 * @property string $serial
 * @property int $user
 * @property double $expectedQty
 * @property float $standardCost
 * @property float $costCounted
 * @property float $costExpected
 * @property Carbon $dateCounted
 * @property Carbon $timeCounted
 * @property float plus_minus
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 *
 * @package App\Models
 */
class InventoryUploadPrecount extends Model
{
    use HasFactory;

    protected $table = 'inventory_precount_upload';

    protected $casts = [
        'count' => 'float',
        'by_weight' => 'int',
        'date_counted' => 'date',
        'time_counted' => 'date'
    ];

    protected $fillable = [
        'tag',
        'tag_status',
        'part',
        'part_description',
        'bin',
        'bin_description',
        'lot_number',
        'serial_number',
        'count',
        'by_weight',
        'uom',
        'user',
        'date_counted',
        'time_counted',
        'note',
        'warehouse',
        'expected_qty',
        'standard_cost',
        'cost_counted',
        'cost_expected',
        'plus_minus',
        'counted'
    ];


    protected function date_counted(): Attribute {
        return Attribute::make(
            set: fn(string $date) => Carbon::createFromFormat('Y-m-d', $date),
        );
    }

    protected function time_counted(): Attribute {
        return Attribute::make(
            set: fn(string $time) => Carbon::createFromFormat('H:i:s', $time),
        );
    }
}


