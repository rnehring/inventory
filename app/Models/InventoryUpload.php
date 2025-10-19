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
 * Class InventoryUpload
 *
 * @property int $id
 * @property string $tag
 * @property string $part
 * @property string $part_description
 * @property string $bin
 * @property string $description
 * @property string $lot_number
 * @property string $serial_number
 * @property float $count
 * @property int $by_weight
 * @property string $uom
 * @property string $activity_before_count
 * @property string $returned
 * @property string $user
 * @property Carbon $date_counted
 * @property Carbon $time_counted
 * @property string $note
 * @property string $has_transactions
 * @property string $sheet_number
 * @property string $tag_status
 * @property string $enable_uom_worksheet
 * @property Carbon $period_end_date
 * @property Carbon $period_start_date
 * @property string $cycle_period
 * @property string $company
 * @property string $warehouse
 * @property string $expected_qty
 * @property string $standard_cost
 * @property string $cost_counted
 * @property string $cost_expected
 * @property string $plus_minus
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class InventoryUpload extends Model
{
    use HasFactory;

    protected $table = 'inventory_upload';

    protected $casts = [
        'count' => 'float',
        'by_weight' => 'int',
        'date_counted' => 'date',
        'time_counted' => 'date',
        'period_end_date' => 'datetime',
        'period_start_date' => 'datetime'
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
        'standard_cost',
        'cost_counted',
        'cost_expected',
        'plus_minus'
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
