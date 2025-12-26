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
 * @property string $lot
 * @property string $serial
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


