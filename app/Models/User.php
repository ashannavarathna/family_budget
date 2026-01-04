<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int|null $role_id
 * @property string|null $remember_token
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Role|null $role
 * @property Collection|Transaction[] $transactions
 * @property Collection|AccountPeriod[] $account_periods
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	protected $table = 'users';

	protected $casts = [
		'role_id' => 'int',
		'email_verified_at' => 'datetime'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'password',
		'role_id',
		'remember_token',
		'email_verified_at'
	];

	// Laravel expects a "password" field; return your column
    public function getAuthPassword()
    {
        return $this->password;
    }

    // If you use "remember" functionality but DB has no remember_token, disable it by overriding:
    public function getRememberTokenName()
    {
        return $this->remember_token; 
    }

	public function role()
	{
		return $this->belongsTo(Role::class);
	}

	public function transactions()
	{
		return $this->hasMany(Transaction::class, 'created_by');
	}

	public function accountPeriods()
	{
		return $this->hasMany(AccountPeriod::class, 'created_by');
	}


    public function isAdmin()
    {
        return $this->role->name === 'admin';
    }

    public function isUser()
    {
        return $this->role->name === 'user';
    }
	
}
