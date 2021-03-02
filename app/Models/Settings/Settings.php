<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
	public $fillable = [
		'address',
		'contact_no',
		'email',
		'seo_title',
		'seo_keyword',
		'seo_description',
		'logo',
		'web_analytics',
		'slider_tagline',
		'default_image',
		'access_coupon',
		'discount_coupon',
		'free_trail_code',
	];
}
