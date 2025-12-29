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
 * @property Carbon $verified_date
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
 * @property float $plus_minus
 * @property bool $top_eighty
 * @property bool $counted
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class PreCount extends Model
{
	protected $table = 'inventory_precount';

	protected $casts = [
		'bin_verified' => 'bool',
		'verified_date' => 'datetime',
        'tag_status' => 'boolean',
        'by_weight' => 'boolean',
        'top_eighty' => 'boolean',
        'counted' => 'boolean',
        'count' => 'float',
        'expected_qty' => 'float',
        'standard_cost' => 'float',
        'cost_counted' => 'float',
        'cost_expected' => 'float',
        'plus_minus' => 'float',
        'date_counted' => 'date',
        // time_counted is stored as TIME in MySQL, returned as string (HH:MM:SS)
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
		'lot_number',
		'serial_number',
		'user',
		'expected_qty',
		'standard_cost',
		'cost_counted',
        'cost_expected',
        'date_counted',
        'time_counted',
        'plus_minus',
        'top_eighty',
        'counted',
        'note',
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

    public function scopeVerified($query)
    {
        return $query->where('bin_verified', 1);
    }

    public function scopeUnverified($query)
    {
        return $query->where('bin_verified', 0)->orWhereNull('bin_verified');
    }
}
