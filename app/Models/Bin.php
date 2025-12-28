<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * @property int $id
 * @property string $warehouse
 * @property string $bin
 *
 * @package App\Models
 */
class Bin extends Model
{
    protected $table = 'valid_bins';

    protected $casts = [];


    protected $fillable = [
        'warehouse',
        'bin'
    ];

}
