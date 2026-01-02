<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PartCost
 *
 * @property int $id
 * @property string $part
 * @property double $price
 *
 * @package App\Models
 */
class PartCost extends Model
{
    protected $table = 'part_prices';

    protected $casts = [];


    protected $fillable = [
        'part',
        'price'
    ];

}


