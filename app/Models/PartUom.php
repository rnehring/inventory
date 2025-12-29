<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Inventory
 *
 * @property int $id
 * @property string $part
 * @property string $uom
 * @property bool $track_lot
 * @property bool $track_serial
 *
 * @package App\Models
 */
class PartUom extends Model
{
    protected $table = 'part_uom';

    protected $casts = [
        'track_lot' => 'boolean',
        'track_serial' => 'boolean',
        'part' => 'string',
        'uom' => 'string',
    ];

    protected $fillable = [
        'part',
        'uom',
        'track_lot',
        'track_serial',
    ];

}
