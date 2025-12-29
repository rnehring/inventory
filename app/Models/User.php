<?php

namespace App\Models;

use App\Enums\UserType;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * @property int $id
 * @property UserType $user_type
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $initials
 * @property string $plant
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use Notifiable;

	protected $table = 'users';

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'first_name',
        'last_name',
        'initials',
        'plant',
        'user_type',
		'email',
		'password',
		'remember_token'
	];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'user_type' => UserType::class,
        ];
    }

    /**
     * Relationships
     */
    public function inventoryCounts()
    {
        return $this->hasMany(Inventory::class, 'user');
    }

    public function precounts()
    {
        return $this->hasMany(PreCount::class, 'user');
    }

    public function noTagParts()
    {
        return $this->hasMany(NoTagPart::class, 'user');
    }

    /**
     * Scopes
     */
    public function scopeManagers($query)
    {
        return $query->where('user_type', UserType::MANAGER->value);
    }

    public function scopeEmployees($query)
    {
        return $query->where('user_type', UserType::EMPLOYEE->value);
    }

    public function scopeByPlant($query, string $plant)
    {
        return $query->where('plant', $plant);
    }

    /**
     * Accessors
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
