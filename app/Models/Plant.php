<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Plant
 *
 * @property int $id
 * @property string $plant
 * @property bool $active
 * @property string $display_name
 *
 * @package App\Models
 */
class Plant extends Model
{
    protected $table = 'plants';

    protected $casts = [];


    protected $fillable = [
        'plant',
        'active',
        'display_name'
    ];

}
