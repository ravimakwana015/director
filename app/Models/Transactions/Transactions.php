<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Laravel\Cashier\Subscription;

class Transactions extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'user_id',
    	'payment_status',
    	'amount',
    	'coupon',
    ];
    protected $dates = [
    	'created_at',
    	'updated_at',
    ];

    public function usertransaction()
    {
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    public function usersubscription()
    {
        return $this->belongsTo(Subscription::class,'user_id', 'user_id');
    }


}
