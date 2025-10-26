<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 * 
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property int $transaction_type_id
 * @property string|null $party
 * @property string|null $receipt_number
 * @property int|null $created_by
 * @property Carbon $date
 * @property string|null $description
 * @property float $amount
 * @property int|null $month
 * @property int|null $year
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User|null $user
 * @property Category $category
 * @property TransactionType $transaction_type
 *
 * @package App\Models
 */
class Transaction extends Model
{
	protected $table = 'transactions';

	protected $casts = [
		'user_id' => 'int',
		'category_id' => 'int',
		'transaction_type_id' => 'int',
		'created_by' => 'int',
		'date' => 'datetime',
		'amount' => 'float',
		'month' => 'int',
		'year' => 'int'
	];

	protected $fillable = [
		'user_id',
		'category_id',
		'transaction_type_id',
		'party',
		'receipt_number',
		'created_by',
		'date',
		'description',
		'amount',
		'month',
		'year'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	public function account()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function transaction_type()
	{
		return $this->belongsTo(TransactionType::class);
	}
}
