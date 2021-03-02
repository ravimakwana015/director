<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Laravel\Cashier\Exceptions\IncompletePayment;
use App\Models\SendUserInviteLink\SendUserInviteLink;
use App\Models\MembershipSubscriptionPlan\MembershipSubscriptionPlan;
use App\Models\Settings\Settings;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Repositories\Admin\User\UserRepository;
use App\Mail\SubscriptionMail;
use App\Mail\SubscriptionCancleMail;
use Auth;
use Psy\Exception\Exception;
use Stripe;

class StripeController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $user;

    /**
     * @param \App\Repositories\Admin\user\UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        $this -> user = $user;
        $this -> middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('home');
    }


    /**
     * Show All Plans.
     *
     * @return Renderable
     * @throws Stripe\Exception\ApiErrorException
     */
    public function plans()
    {

        if (Auth ::user() -> subscribed('main')) {
            return redirect() -> route('plans');
        } else {

            Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
            $plans = Stripe\Plan ::all();
            $adminplans = MembershipSubscriptionPlan ::all();

            return view('subscription', compact('plans', 'adminplans'));
        }
    }

    /**
     * Select Payment method.
     *
     * @return Renderable
     * @throws Stripe\Exception\ApiErrorException
     */
    public function getPlans($planId)
    {

        if (Auth ::user() -> subscribed('main')) {
            return redirect() -> route('plans');
        } else {

            Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
            $plan = Stripe\Plan ::retrieve($planId);

            return view('discount', compact('plan'));
            // return view('plan',compact('plan'));
        }

    }

    /**
     * Get Plans.
     *
     * @param $planId
     * @return Factory|RedirectResponse|View
     */
    public function payment($planId)
    {

        if (Auth ::user() -> subscribed('main')) {
            return redirect() -> route('plans');
        } else {
            try {
                Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
                $plan = Stripe\Plan ::retrieve($planId);
                return view('plan', compact('plan'));
            } catch (\Stripe\Exception\RateLimitException $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            } catch (\Stripe\Exception\AuthenticationException $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            } catch (\Stripe\Exception\ApiConnectionException $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            } catch (\Stripe\Exception\ApiErrorException $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            } catch (Exception $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            }
        }

    }

    /**
     * Get Plans.
     *
     * @param $planId
     * @return Factory|RedirectResponse|View
     */
    public function trailPayment($planId)
    {

        try {
            Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
            $plan = Stripe\Plan ::retrieve($planId);
            return view('trail-plan', compact('plan'));
        } catch (\Stripe\Exception\RateLimitException $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        } catch (Exception $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        }

    }


    /**
     * @param Request $request
     */
    public function checkcoupon(Request $request)
    {
        $coupon = Settings ::all();
        $setcode = $coupon[0]['discount_coupon'];
        $free_trail_code = $coupon[0]['free_trail_code'];
        $applycoupon = $request -> all();

        $user = Auth ::user();
        if ($setcode == $applycoupon['coupon_codes']) {
            Auth ::user() -> update(['status' => 1]);
            DB ::table('subscriptions') -> insert([
                'user_id' => $user -> id,
                'name' => 'main',
                'stripe_status' => 'active',
                'stripe_id' => $user -> username,
                'stripe_plan' => $applycoupon['plan_name'],
                'quantity' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            DB ::table('transactions') -> insert([
                'user_id' => $user -> id,
                'payment_status' => 1,
                'amount' => 0,
                'coupon' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            // Send mail to User
            Mail ::to($user['email']) -> send(new SubscriptionMail($user));

            echo json_encode(array('status' => 1));
        } elseif ($free_trail_code == $applycoupon['coupon_codes']) {
            DB ::table('subscriptions') -> insert([
                'user_id' => $user -> id,
                'name' => 'main',
                'stripe_status' => 'active',
                'stripe_id' => $user -> username,
                'stripe_plan' => $applycoupon['plan_name'],
                'quantity' => 1,
                'trial_ends_at' => Carbon ::now() -> addDays(6),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            DB ::table('transactions') -> insert([
                'user_id' => $user -> id,
                'payment_status' => 1,
                'amount' => 0,
                'coupon' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            Auth ::user() -> update(['status' => 1, 'planid' => $applycoupon['plan_id']]);
            // Send mail to User
            Mail ::to($user['email']) -> send(new SubscriptionMail($user));

            echo json_encode(array('status' => 1));
        } else {
            echo json_encode(array('status' => 0));
        }
    }


    /**
     * subscribed User to  Plans.
     * @param Request $request
     * @return RedirectResponse
     */
    public function orderPost(Request $request)
    {

        if (Auth ::user() -> subscribed('main')) {
            return redirect() -> route('plans');
        } else {
            Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
            $user = Auth ::user();
            $input = $request -> all();
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

                try {

                    if ($coupon = $request -> get('coupon')) {
                        $user -> newSubscription('main', $input['plan']) -> withCoupon($coupon) -> create($paymentMethod -> id);
                    } else {

                        $user -> newSubscription('main', $input['plan']) -> create($paymentMethod -> id);
                    }
                } catch (\Stripe\Exception\RateLimitException $e) {
                    return redirect() -> back() -> with('error', $e -> getMessage());
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    return redirect() -> back() -> with('error', $e -> getMessage());
                } catch (\Stripe\Exception\AuthenticationException $e) {
                    return redirect() -> back() -> with('error', $e -> getMessage());
                } catch (\Stripe\Exception\ApiConnectionException $e) {
                    return redirect() -> back() -> with('error', $e -> getMessage());
                } catch (\Stripe\Exception\ApiErrorException $e) {
                    return redirect() -> back() -> with('error', $e -> getMessage());
                } catch (Exception $e) {
                    return redirect() -> back() -> with('error', $e -> getMessage());
                }

                // if ($user->subscribed('main')) {
                Auth ::user() -> update(['status' => 1]);
                // Send mail to User
                Mail ::to($user['email']) -> send(new SubscriptionMail($user));

                return redirect() -> route('thankyou') -> with('success', 'Subscription has been completed. We look forward to having you on our platform!');
            } catch (\Stripe\Exception\RateLimitException $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            } catch (\Stripe\Exception\AuthenticationException $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            } catch (\Stripe\Exception\ApiConnectionException $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            } catch (\Stripe\Exception\ApiErrorException $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            } catch (Exception $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            }
        }
    }

    /**
     * subscribed User to  Plans.
     * @param Request $request
     * @return RedirectResponse
     */
    public function trailOrderPost(Request $request)
    {

        Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
        $user = Auth ::user();
        $input = $request -> all();
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

            try {
                $user -> newSubscription('main', $user -> planid) -> create($paymentMethod -> id);
            } catch (\Stripe\Exception\RateLimitException $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            } catch (\Stripe\Exception\AuthenticationException $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            } catch (\Stripe\Exception\ApiConnectionException $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            } catch (\Stripe\Exception\ApiErrorException $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            } catch (Exception $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            }

            // if ($user->subscribed('main')) {
            Auth ::user() -> update(['status' => 1]);

//            $subscription = DB ::table('subscriptions') -> where('user_id', Auth ::user() -> id) -> first();

//            $subscription -> update(['stripe_status' => 'active']);
            // Send mail to User
            Mail ::to($user['email']) -> send(new SubscriptionMail($user));

            return redirect() -> route('dashboard') -> with('success', 'Membership Activated Successfully');
        } catch (\Stripe\Exception\RateLimitException $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        } catch (Exception $e) {
            return redirect() -> back() -> with('error', $e -> getMessage());
        }
    }

    /**
     * After subscription tank You Page
     *
     */
    public function thankYou()
    {
        Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
        if (Auth ::user() -> owner -> stripe_id == Auth ::user() -> username) {
            $customPlan = Auth ::user() -> owner;
            return view('thankyou', compact('customPlan'));

        } else {
            try {
                $plan = Stripe\Plan ::retrieve(Auth ::user() -> subscription('main') -> stripe_plan);
            } catch (\Stripe\Exception\RateLimitException $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            } catch (\Stripe\Exception\AuthenticationException $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            } catch (\Stripe\Exception\ApiConnectionException $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            } catch (\Stripe\Exception\ApiErrorException $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            } catch (Exception $e) {
                return redirect() -> back() -> with('error', $e -> getMessage());
            }
            return view('thankyou', compact('plan'));
        }
    }


    /**
     * Cancle subscription
     * @param Request $request
     * @return RedirectResponse
     */
    public function cancleMembership(Request $request)
    {
        $input = $request -> all();
        if (isset($input['feedback']) && $input['feedback'] != '') {
            DB ::table('cancel_membership_feedback') -> insert([
                'user_id' => Auth ::user() -> id,
                'feedback' => $input['feedback'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        Auth ::user() -> subscription('main') -> cancel();
        Mail ::to(Auth ::user() -> email) -> send(new SubscriptionCancleMail(Auth ::user(), 'user'));
        $setting = settings();
        Mail ::to($setting[0] -> email) -> send(new SubscriptionCancleMail(Auth ::user(), 'admin'));

        return back() -> with('success', 'Membership has been Cancelled Successfully');
    }

    /**
     * Resume subscription
     *
     */
    public function resumeMembership()
    {
        if (Auth ::user() -> hasPaymentMethod()) {
            Auth ::user() -> subscription('main') -> resume();
            return back() -> with('success', 'Membership has been Resumed Successfully');
        } else {
            return back() -> with('error', 'Please Add a Payment Method');
        }
    }
}
