<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * AccountPeriod
 * 
 * @property int $id
 * @property int $user_id
 * @property int $period_year
 * @property int $period_month
 * @property string $period_label
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property double $opening_balance
 * @property double $closing_balance
 * @property boolean $is_closed
 */

class AccountPeriod extends Model
{

    protected $table = 'account_periods';

    protected $fillable = [
        'user_id',
        'period_year',
        'period_month',
        'period_label',
        'start_date',
        'end_date',
        'opening_balance',
        'closing_balance',
        'is_closed',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_closed'  => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
