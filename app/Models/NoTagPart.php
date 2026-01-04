<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NoTagPart
 *
 * @property int $id
 * @property string $tag
 * @property string $part
 * @property string $bin
 * @property float $count
 * @property string $uom
 * @property bool $by_weight
 * @property string $warehouse
 * @property string $lot_number
 * @property string $serial_number
 * @property int $user
 * @property string $note
 * @property Carbon $date_counted
 * @property Carbon $time_counted
 * @property double $expected_qty
 * @property float $standard_cost
 * @property float $cost_counted
 * @property float $plus_minus
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property bool $possible_dupe
 *
 * @package App\Models
 */
class NoTagPart extends Model
{
    protected $table = 'no_tag_parts';

    protected $casts = [
        'count' => 'float',
        'by_weight' => 'boolean',
        'expected_qty' => 'float',
        'standard_cost' => 'float',
        'cost_counted' => 'float',
        'plus_minus' => 'float',
        'date_counted' => 'date',
        // time_counted is stored as TIME in MySQL, returned as string (HH:MM:SS)
    ];

    protected $fillable = [
        'tag',
        'part',
        'bin',
        'count',
        'uom',
        'by_weight',
        'warehouse',
        'lot_number',
        'serial_number',
        'user',
        'note',
        'date_counted',
        'time_counted',
        'expected_qty',
        'standard_cost',
        'cost_counted',
        'plus_minus',
    ];

    /**
     * Relationships
     */
    public function counter()
    {
        return $this->belongsTo(User::class, 'user');
    }

    /**
     * Scopes
     */
    public function scopeByWarehouse($query, string $warehouse)
    {
        return $query->where('warehouse', $warehouse);
    }

    public function scopeByPart($query, string $part)
    {
        return $query->where('part', $part);
    }
}
