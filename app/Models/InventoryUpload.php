<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InventoryUpload
 *
 * @property string $tag
 * @property bool $tag_printed
 * @property string $part
 * @property string $bin
 * @property string $lot_number
 * @property string $serial_number
 * @property string $uom
 * @property string $note
 * @property string $warehouse
 * @property string $expected_qty
 * @property string $standard_cost
 * @property string $sys_rev_id
 * @property string $sys_row_id
 *
 * @package App\Models
 */
class InventoryUpload extends Model
{
    use HasFactory;

    protected $table = 'inventory_upload';

    protected $fillable = [
        'tag',
        'tag_printed',
        'part',
        'bin',
        'lot_number',
        'serial_number',
        'uom',
        'note',
        'warehouse',
        'expected_qty',
        'standard_cost',
        'sys_rev_id',
        'sys_row_id',
    ];

}
