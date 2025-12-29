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
 * @property bool $tag_printed
 * @property string $part
 * @property string $bin
 * @property string $warehouse
 * @property string $lot_number
 * @property string $serial_number
 * @property float $count
 * @property bool $by_weight
 * @property string $uom
 * @property int $user
 * @property Carbon $date_counted
 * @property Carbon $time_counted
 * @property string $note
 * @property double $expected_qty
 * @property float $standard_cost
 * @property float $cost_counted
 * @property float $cost_expected
 * @property float $plus_minus
 * @property bool $top_eighty
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property bool $counted
 * @property string $sys_rev_id
 * @property string $sys_row_id
 *
 * @package App\Models
 */
class Inventory extends Model
{
	protected $table = 'inventory';

	protected $casts = [
		'count' => 'float',
		'date_counted' => 'date',
		// time_counted is stored as TIME in MySQL, returned as string (HH:MM:SS)
        'tag_printed' => 'boolean',
        'by_weight' => 'boolean',
        'top_eighty' => 'boolean',
        'counted' => 'boolean',
        'expected_qty' => 'float',
        'standard_cost' => 'float',
        'cost_counted' => 'float',
        'cost_expected' => 'float',
        'plus_minus' => 'float',
	];

	protected $fillable = [
		'tag',
        'tag_printed',
		'part',
		'bin',
		'warehouse',
		'lot_number',
		'serial_number',
		'count',
		'by_weight',
		'uom',
		'user',
		'date_counted',
		'time_counted',
		'note',
		'expected_qty',
		'standard_cost',
        'cost_counted',
        'cost_expected',
        'plus_minus',
        'top_eighty',
        'counted',
        'sys_rev_id',
        'sys_row_id',
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

    public function scopeCounted($query)
    {
        return $query->where('counted', 1);
    }

    public function scopeUncounted($query)
    {
        return $query->where('counted', 0)->orWhereNull('counted');
    }

    public function scopeTopEighty($query)
    {
        return $query->where('top_eighty', 1);
    }

    public function scopeByPart($query, string $part)
    {
        return $query->where('part', $part);
    }

    public function scopeByBin($query, string $bin)
    {
        return $query->where('bin', $bin);
    }

    public function scopeWithCosts($query)
    {
        return $query->whereNotNull('cost_counted');
    }

    public function scopeVariances($query)
    {
        return $query->whereColumn('count', '!=', 'expected_qty');
    }
}
