<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\ResponseController as ResponseController;
use Carbon\Carbon;

class AuthController extends ResponseController
{
    //login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());
        }
        $login = request()->input('email');
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $login]);
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $credentials = request(['email', 'password']);
            if (Auth::attempt($credentials)) {
                $user = $request->user();
                $user->update([
                    'last_login_at' => Carbon::now()->toDateTimeString(),
                    'last_login_ip' => $request->getClientIp()
                ]);
                if ($user->subscribed('main')) {
                    $user->update(['status' => 1]);
                } else {
                    $user->update(['status' => 0]);
                }
                $success['token'] =  $user->createToken('token')->accessToken;
                return $this->sendResponse($success);

            } else {
                $error = "Invalid Email or Password, Please try again";
                return $this->sendError($error, 401);
            }
        } else {
            $credentials = request(['username', 'password']);
            if (Auth::attempt($credentials)) {
                $user = $request->user();
                $user->update([
                    'last_login_at' => Carbon::now()->toDateTimeString(),
                    'last_login_ip' => $request->getClientIp()
                ]);
                if ($user->subscribed('main')) {
                    $user->update(['status' => 1]);
                } else {
                    $user->update(['status' => 0]);
                }
                $user = $request->user();
                $success['token'] =  $user->createToken('token')->accessToken;
                return $this->sendResponse($success);
            } else {
                $error = "Invalid Email or Password, Please try again";
                return $this->sendError($error, 401);
            }
        }
    }

    //logout
    public function logout(Request $request)
    {
        $isUser = $request->user()->token()->revoke();
        if($isUser){
            $success['message'] = "Successfully logged out.";
            return $this->sendResponse($success);
        }
        else{
            $error = "Something went wrong.";
            return $this->sendResponse($error);
        }
    }
}
