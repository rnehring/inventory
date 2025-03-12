<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $initials
 * @property string $company
 * @property string $location
 * @property int $user_type
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	protected $table = 'users';

	protected $casts = [];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'first_name',
        'last_name',
        'initials',
        'company',
        'location',
        'user_type',
		'email',
		'password',
		'remember_token'
	];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

}
