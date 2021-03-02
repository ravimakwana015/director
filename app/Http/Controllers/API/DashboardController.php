<?php

namespace App\Http\Controllers\API;

use App\Models\UsersPersonalityTraits\UsersPersonalityTraits;
use App\Notifications\PersonalityTraits;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\API\ResponseController as ResponseController;
use Spatie\Activitylog\Models\Activity;
use App\Models\Like\Like;
use App\Models\Country\Country;
use App\User;
use Auth;
use Stripe;
use Image;
use Stripe\Exception\ApiErrorException;
use Validator;

class DashboardController extends ResponseController
{
    /**
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function index()
    {
        if (Auth ::user() -> owner -> stripe_id == Auth ::user() -> username) {
            $count = Like ::where('profile_id', Auth ::user() -> id) -> count();
            $customPlan = Auth ::user() -> owner;
            $userDetails = Auth ::user();
            $notifications = Auth ::user() -> notifications;
            $invites = Auth ::user() -> invites;

            $data = [
                'customPlan' => $customPlan,
                'count' => $count,
                'userDetails' => $userDetails,
                'notifications' => $notifications,
                'invites' => $invites
            ];
            return $this -> sendResponse($data);
        } else {
            Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
            if (Auth ::user() -> subscription('main')) {
                $count = Like ::where('profile_id', Auth ::user() -> id) -> count();
                $plan = Stripe\Plan ::retrieve(Auth ::user() -> subscription('main') -> stripe_plan);
                $subscription = \Stripe\Subscription ::retrieve(Auth ::user() -> subscription('main') -> stripe_id);
                $cards = [];
                $defaultPaymentMethod = '';
                if (Auth ::user() -> hasPaymentMethod()) {
                    foreach (Auth ::user() -> paymentMethods() as $key => $value) {
                        $cards[] = $value -> asStripePaymentMethod();
                    }
                    $defaultPaymentMethod = Auth ::user() -> defaultPaymentMethod() -> asStripePaymentMethod() -> id;
                }
                $userDetails = Auth ::user();
                $notifications = Auth ::user() -> notifications;
                $invites = Auth ::user() -> invites;
                $data = [
                    'plan' => $plan,
                    'count' => $count,
                    'subscription' => $subscription,
                    'cards' => $cards,
                    'defaultPaymentMethod' => $defaultPaymentMethod,
                    'userDetails' => $userDetails,
                    'notifications' => $notifications,
                    'invites' => $invites
                ];
                return $this -> sendResponse($data);
            } else {
                return $this -> sendError('Error, Please Login');
            }
        }
    }

    /**
     * Add User Stripe Card.
     * @param Request $request
     * @return JsonResponse
     */
    public function addCard(Request $request)
    {
        $validator = Validator ::make($request -> all(), [
            'number' => 'required',
            'exp_month' => 'required',
            'exp_year' => 'required',
            'cvc' => 'required',
        ], [
            'number.required' => 'Card Number must be required',
            'exp_month.required' => 'Card expire Month must be required',
            'exp_year.required' => 'Card expire year must be required',
            'cvc.required' => 'Card cvc must be required',
        ]);

        if ($validator -> fails()) {
            return $this -> sendError($validator -> errors());
        }

        $input = $request -> all();

        Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
        try {
            $paymentMethod = \Stripe\PaymentMethod ::create([
                'type' => 'card',
                'card' => [
                    'number' => $input['number'],
                    'exp_month' => $input['exp_month'],
                    'exp_year' => $input['exp_year'],
                    'cvc' => $input['cvc']
                ]
            ]);
            Auth ::user() -> addPaymentMethod($paymentMethod);
            if (!Auth ::user() -> hasPaymentMethod()) {
                Auth ::user() -> updateDefaultPaymentMethod($paymentMethod);
            }
            return $this -> sendSuccess('Card Add Successfully');
        } catch (\Stripe\Exception\RateLimitException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (ApiErrorException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (Exception $e) {
            return $this -> sendError($e -> getMessage());
        }
    }

    /**
     * get User Stripe Card.
     * @param Request $request
     * @return JsonResponse
     */
    public function getCard(Request $request)
    {
        $validator = Validator ::make($request -> all(), [
            'cardid' => 'required',
        ], [
            'cardid.required' => 'Card id must be required',
        ]);

        if ($validator -> fails()) {
            return $this -> sendError($validator -> errors());
        }
        $input = $request -> all();

        Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
        try {
            $paymentMethod = \Stripe\PaymentMethod ::retrieve(
                $input['cardid']
            );
            return $this -> sendResponse($paymentMethod);
        } catch (\Stripe\Exception\RateLimitException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (ApiErrorException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (Exception $e) {
            return $this -> sendError($e -> getMessage());
        }

    }

    /**
     * Update User Stripe Card.
     * @param Request $request
     * @return JsonResponse
     */
    public function updateCard(Request $request)
    {
        $validator = Validator ::make($request -> all(), [
            'cardid' => 'required',
            'exp_month' => 'required',
            'exp_year' => 'required',
        ], [
            'cardid.required' => 'Card id must be required',
            'exp_month.required' => 'Card expire Month must be required',
            'exp_year.required' => 'Card expire year must be required',
        ]);
        if ($validator -> fails()) {
            return $this -> sendError($validator -> errors());
        }

        $input = $request -> all();

        Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
        try {
            $paymentMethodUpdate = Stripe\PaymentMethod ::update(
                $input['cardid'],
                [
                    'card' =>
                        [
                            'exp_month' => $input['exp_month'],
                            'exp_year' => $input['exp_year'],
                        ]
                ]
            );
            return $this -> sendSuccess('Card Updated Successfully');
        } catch (\Stripe\Exception\RateLimitException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (ApiErrorException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (Exception $e) {
            return $this -> sendError($e -> getMessage());
        }

    }

    /**
     * Delete User Stripe Card.
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function deleteCard(Request $request)
    {
        $validator = Validator ::make($request -> all(), [
            'cardid' => 'required',
        ], [
            'cardid.required' => 'Card id must be required',
        ]);

        if ($validator -> fails()) {
            return $this -> sendError($validator -> errors());
        }
        $input = $request -> all();
        Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
        try {
            $paymentMethod = \Stripe\PaymentMethod ::retrieve(
                $input['cardid']
            );
            Auth ::user() -> removePaymentMethod($paymentMethod);
            return $this -> sendSuccess('Card Deleted Successfully.');
        } catch (\Stripe\Exception\RateLimitException $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        } catch (ApiErrorException $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        } catch (Exception $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        }

    }

    /**
     * Set Default Stripe Card.
     * @param Request $request
     * @return JsonResponse
     */
    public function setDefaultCard(Request $request)
    {
        $validator = Validator ::make($request -> all(), [
            'cardid' => 'required',
        ], [
            'cardid.required' => 'Card id must be required',
        ]);

        if ($validator -> fails()) {
            return $this -> sendError($validator -> errors());
        }
        $input = $request -> all();
        Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
        try {
            $paymentMethod = \Stripe\PaymentMethod ::retrieve(
                $input['cardid']
            );
            Auth ::user() -> updateDefaultPaymentMethod($paymentMethod);
            return $this -> sendSuccess('Card Set as Default Successfully');
        } catch (\Stripe\Exception\RateLimitException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (ApiErrorException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (Exception $e) {
            return $this -> sendError($e -> getMessage());
        }

    }

    /**
     * Get Country.
     * @param Request $request
     * @return JsonResponse
     */
    public function getCountry(Request $request)
    {

        $validator = Validator ::make($request -> all(), [
            'search' => 'required',
        ], [
            'search.required' => 'Country name must be required',
        ]);

        if ($validator -> fails()) {
            return $this -> sendError($validator -> errors());
        }
        $countries = Country ::where('name', 'like', '%' . $request -> all()['search'] . '%') -> pluck('name', 'id') -> toArray();
        $countriesDdata = [];
        foreach ($countries as $key => $country) {

            $countriesDdata[] = array("id" => $country, "text" => $country);
        }
        return $this -> sendResponse($countriesDdata);
    }

    /**
     * Increase Personality.
     * @param Request $request
     * @return JsonResponse
     */
    public function increasePersonality(Request $request)
    {
        $validator = Validator ::make($request -> all(), [
            'user_id' => 'required',
        ], [
            'user_id.required' => 'User id must be required',
        ]);

        if ($validator -> fails()) {
            return $this -> sendError($validator -> errors());
        }

        $input = $request -> all();
        $personality = UsersPersonalityTraits ::where('user_id', $input['userid']) -> first();
        $pruser = User ::find($input['userid']);
        if (isset($personality)) {
            if ($personality -> click_by != '') {
                $oldClickedUser = json_decode($personality -> click_by, true);
                if (isset($oldClickedUser[Auth ::id()])) {

                    $userClickTraits = $oldClickedUser[Auth ::id()];
                    if (!in_array($input['traits'], $userClickTraits)) {

                        $traitsCount = $personality[$input['traits']] + 1;
                        $oldClickedUser[Auth ::id()][] = $input['traits'];
                        $click_by = json_encode($oldClickedUser);
                        $personality -> update([
                            $input['traits'] => $traitsCount,
                            'click_by' => $click_by
                        ]);
                    }
                    $msg = Auth ::user() -> first_name . ' ' . Auth ::user() -> last_name . ' Improve Your Personality Traits';
                    $pruser -> notify(new PersonalityTraits($msg));

                    activity()
                        -> causedBy($pruser)
                        -> performedOn($personality)
                        -> withProperties(['name' => Auth ::user() -> first_name . ' ' . Auth ::user() -> last_name, 'username' => Auth ::user() -> username])
                        -> log('Improve Your Personality');
                    return $this -> sendResponse($personality[$input['traits']]);
                    // return json_encode(array('status'=>1,'personality'=>$personality[$input['traits']]));
                } else {
                    $traitsCount = $personality[$input['traits']] + 1;
                    $oldClickedUser[Auth ::id()][] = $input['traits'];
                    $click_by = json_encode($oldClickedUser);
                    $personality -> update([
                        $input['traits'] => $traitsCount,
                        'click_by' => $click_by
                    ]);
                    $msg = Auth ::user() -> first_name . ' ' . Auth ::user() -> last_name . ' Improve Your Personality Traits';
                    $pruser -> notify(new PersonalityTraits($msg));

                    activity()
                        -> causedBy($pruser)
                        -> performedOn($personality)
                        -> withProperties(['name' => Auth ::user() -> first_name . ' ' . Auth ::user() -> last_name, 'username' => Auth ::user() -> username])
                        -> log('Improve Your Personality');
                    return $this -> sendResponse($personality[$input['traits']]);
                    // return json_encode(array('status'=>1,'personality'=>$personality[$input['traits']]));
                }
            } else {
                $traitsCount = $personality[$input['traits']] + 1;
                $click_by = json_encode(array(Auth ::id() => array($input['traits'])));
                $personality -> update([
                    $input['traits'] => $traitsCount,
                    'click_by' => $click_by
                ]);
            }
            $msg = Auth ::user() -> first_name . ' ' . Auth ::user() -> last_name . ' Improve Your Personality Traits';
            $pruser -> notify(new PersonalityTraits($msg));
            activity()
                -> causedBy($pruser)
                -> performedOn($personality)
                -> withProperties(['name' => Auth ::user() -> first_name . ' ' . Auth ::user() -> last_name, 'username' => Auth ::user() -> username])
                -> log('Improve Your Personality');
            return $this -> sendResponse($personality[$input['traits']]);
            // return json_encode(array('status'=>1,'personality'=>$personality[$input['traits']]));
        } else {
            return $this -> sendError('Personality Not Found');
        }

    }

    /**
     * Show Notification
     *
     */
    public function showNotification()
    {
        $userNotifications = Auth ::user() -> notifications() -> paginate(10);
        return $this -> sendResponse($userNotifications);
    }

    /**
     * Read Notification
     *
     */
    public function readNotification()
    {
        $notification = auth() -> user() -> unreadNotifications;
        foreach ($notification as $key => $value) {
            $id = $value -> id;
            auth() -> user() -> unreadNotifications -> where('id', $id) -> markAsRead();
        }
        return $this -> sendResponse(['message' => 'Successfully Read Notification']);
    }

    /**
     * @return JsonResponse
     */
    public function collectReward()
    {

        $alreadyCollectPointsToday = Like ::where('like_user_type', 'reward_point') -> where('profile_id', Auth ::user() -> id) -> where('created_at', 'like', '%' . date('Y-m-d') . '%') -> first();
        if (isset($alreadyCollectPointsToday)) {
            return $this -> sendError(['You have already collected your Daily Reward for today. Come back tomorrow for your new Reward!']);
        } else {
            $ran = array(1, 2, 3);
            $randomPoint = $ran[array_rand($ran, 1)];
            $ip = request() -> ip();
            Like ::create([
                'user_id' => Auth ::user() -> id,
                'profile_id' => Auth ::user() -> id,
                'like_user_type' => 'reward_point',
                'ip_address' => $ip,
                'count' => $randomPoint
            ]);
            $response['message'] = 'Reward Point Added Successfully';
            $response['randomPoint'] = $randomPoint;
            return $this -> sendResponse($response);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function changeMembershipPlan(Request $request)
    {
        $validator = Validator ::make($request -> all(), [
            'plan_id' => 'required',
        ], [
            'plan_id.required' => 'Plan Id is required',
        ]);

        if ($validator -> fails()) {
            return $this -> sendError($validator -> errors()->all());
        }
        $input = $request -> all();
        try {
            Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
            $plan = Stripe\Plan ::retrieve($input['plan_id']);
            $user = Auth ::user();
            if($user->owner->stripe_plan==$input['plan_id']){
                return $this -> sendError(['This Plan Already Activated']);
            }
            $user -> subscription('main') -> swap($input['plan_id']);
            return $this -> sendResponse(['message' => 'Membership Updated Successfully.']);
        } catch (\Stripe\Exception\RateLimitException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return $this -> sendError($e -> getMessage());
        } catch (Exception $e) {
            return $this -> sendError($e -> getMessage());
        }

    }
}
