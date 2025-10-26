<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * 
 * @property int $id
 * @property string $name
 * @property int $transaction_type_id
 * @property Carbon|null $created_at
 * 
 * @property TransactionType $transaction_type
 * @property Collection|Transaction[] $transactions
 *
 * @package App\Models
 */
class Category extends Model
{
	protected $table = 'categories';
	public $timestamps = true;

	protected $casts = [
		'transaction_type_id' => 'int'
	];

	protected $fillable = [
		'name',
		'transaction_type_id'
	];

	public function transaction_type()
	{
		return $this->belongsTo(TransactionType::class);
	}

	public function transactions()
	{
		return $this->hasMany(Transaction::class);
	}
}
