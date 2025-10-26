<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TransactionType
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * 
 * @property Collection|Category[] $categories
 * @property Collection|Transaction[] $transactions
 *
 * @package App\Models
 */
class TransactionType extends Model
{
	protected $table = 'transaction_types';
	public $timestamps = true;

	protected $fillable = [
		'name'
	];

	public function categories()
	{
		return $this->hasMany(Category::class);
	}

	public function transactions()
	{
		return $this->hasMany(Transaction::class);
	}
}
