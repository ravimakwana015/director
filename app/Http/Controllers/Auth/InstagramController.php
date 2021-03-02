<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use App\Instagram;

class InstagramController extends Controller
{
    public function redirectToInstagramProvider()
    {
        return Socialite::with('instagram')->scopes([
            "basic"])->redirect();
    }

    public function handleProviderInstagramCallback()
    {
        $insta = Socialite::driver('instagram')->user();

        $instagramUser=Instagram::where('insta_id',$insta->id)->first();

        $details = [
            "access_token" => $insta->token
        ];

        if(isset($instagramUser)){
            Auth::loginUsingId($instagramUser->user_id);
            $instagramUser->update($details);
        }else{
            if($insta->email!=''){
                $email=$insta->email;
            }else{
                $email=$insta->id.'@instagram.com';
            }
            $user=User::create([

                'first_name' =>    $insta->name,
                'last_name'  =>    $insta->name,
                'email'      =>    $email,
                'password'   =>    '',
                'user_type'  =>    '1s',

            ]);
            Auth::loginUsingId($user->id);
            $details = [
                "access_token" => $insta->token,
                "insta_id" => $insta->id,
                "user_id" => $user->id
            ];
            Auth::user()->instagram()->create($details);
        }
        return redirect('/');
    }
}
