<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Laravel\Cashier\Billable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Instagram;
use Laravel\Cashier\Subscription;
use App\Models\UsersPersonalityTraits\UsersPersonalityTraits;
use App\Models\PhotoGallery\PhotoGallery;
use App\Models\VideoGallery\VideoGallery;
use App\Models\Like\Like;
use App\Notifications\Registeruser;
use App\Models\Invites\Invites;
use App\Models\Transactions\Transactions;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Spatie\Activitylog\Models\Activity;
use App\Models\UserFeed\UserFeed;
use Illuminate\Database\Eloquent\SoftDeletes; //add this line

class User extends Authenticatable
{
    use Notifiable, Billable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'city',
        'mobile',
        'password',
        'user_type',
        'refer_code',
        'skills',
        'caption',
        'experience',
        'gender',
        'playing_age',
        'languages',
        'genre',
        'acents',
        'stars',
        'favourite_directors',
        'top_songs',
        'profile_description',
        'profile_picture',
        'status',
        'last_login_at',
        'last_login_ip',
        'country',
        'height',
        'eye_colour',
        'biography',
        'goals',
        'interests',
        'actor_questions',
        'musician_questions',
        'model_questions',
        'role_type',
        'favourite_films',
        'instagram_link',
        'cv',
        'top_musicians',
        'favourite_models',
        'favourite_brands',
        'hair_colour',
        'instrument',
        'crew_type',
        'other_professions',
        'private_user',
        'access_code',
        'planid',
        'verify',
        'representation',
        'model_name',
        'model_link',
        'user_agreement',
        'privacy_policy',
    ];
    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function usertransactions()
    {
        return $this->belongsTo(Transactions::class,'id', 'user_id');
    }

    public function friendsOfMine()
    {
        return $this -> belongsToMany('App\User', 'friends', 'user_id', 'friend_id');
    }

    public function friendOf()
    {
        return $this -> belongsToMany('App\User', 'friends', 'friend_id', 'user_id');
    }

    public function friends()
    {
        return $this -> friendsOfMine -> merge($this -> friendOf);
    }

    public function friendsOfMineNetwork()
    {
        return $this -> belongsToMany('App\User', 'user_networks', 'user_id', 'friend_id') -> where('user_networks.status', 1)-> where('users.status', 1);
    }

    public function friendOfNetwork()
    {
        return $this -> belongsToMany('App\User', 'user_networks', 'friend_id', 'user_id') -> where('user_networks.status', 1)-> where('users.status', 1);
    }

    public function networkFriends()
    {
        return $this -> friendsOfMineNetwork -> merge($this -> friendOfNetwork);
    }

    public function messages()
    {
        return $this -> hasMany(Message::class);
    }

    public function instagram()
    {
        return $this -> hasOne(Instagram::class, 'user_id', 'id');
    }

    public function owner()
    {
        return $this -> belongsTo(Subscription::class, 'id', 'user_id');
    }

    public function userActivity()
    {
        return $this -> hasMany(Activity::class, 'causer_id') -> orderBy('created_at', 'desc') -> take(5);
    }

    public function userAllActivity()
    {
        return $this -> hasMany(Activity::class, 'causer_id') -> orderBy('created_at', 'desc');
    }

    public function personality()
    {
        return $this -> hasOne(UsersPersonalityTraits::class, 'user_id', 'id');
    }

    public function gallery()
    {
        return $this -> hasMany(PhotoGallery::class, 'user_id', 'id');
    }

    public function invites()
    {
        return $this -> hasMany(Invites::class, 'user_id', 'id');
    }

    public function userFeed()
    {
        return $this -> hasMany(UserFeed::class, 'user_id', 'id');
    }

    public function videoGallery()
    {
        return $this -> hasMany(VideoGallery::class, 'user_id', 'id');
    }

    public function likeCount()
    {
        return $this -> hasMany(Like::class, 'profile_id', 'id')-> orderby('count', 'DESC');
    }

    /**
     * Sends the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this -> notify(new CustomPassword($token));
    }
}

class CustomPassword extends ResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            -> line('Oops!!! You seem to have forgotten your password. Not to worry at all. You can use this link to reset the password and you will be back on in no time. ')
            -> action('Reset Password', url(config('app.url') . route('password.reset', $this -> token, false)))
            -> line('If you need any more help, please do get in touch');
    }
}
