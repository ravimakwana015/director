<?php

namespace App\Models\MembershipSubscriptionPlan;

use Illuminate\Database\Eloquent\Model;

class MembershipSubscriptionPlan extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
     	"amount",
     	"interval",
     	"status",
     	"name",
     	"description",
     	"currency",
     	"plan_id",
          "trial_period_days",
          "short_description"
     ];
     protected $dates = [
     	'created_at',
     	'updated_at',
     ];
 }
