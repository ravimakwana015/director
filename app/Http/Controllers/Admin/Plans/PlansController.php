<?php
namespace App\Http\Controllers\Admin\Plans;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MembershipSubscriptionPlan\MembershipSubscriptionPlan;
use Stripe;


class PlansController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    	try {
    		$plans=Stripe\Plan::all();
    		return view('admin.plans.index',compact('plans'));
    	} catch (\Stripe\Exception\RateLimitException $e) {
    		return view('admin.plans.index')->with('error',$e->getMessage());
    	} catch (\Stripe\Exception\InvalidRequestException $e) {
    		return view('admin.plans.index')->with('error',$e->getMessage());
    	} catch (\Stripe\Exception\AuthenticationException $e) {
    		return view('admin.plans.index')->with('error',$e->getMessage());
    	} catch (\Stripe\Exception\ApiConnectionException $e) {
    		return view('admin.plans.index')->with('error',$e->getMessage());      
    	} catch (\Stripe\Exception\ApiErrorException $e) {
    		return view('admin.plans.index')->with('error',$e->getMessage());
    	} catch (Exception $e) {
    		return view('admin.plans.index')->with('error',$e->getMessage());
    	}

    }


    /**
     * @param \App\Http\Requests\Admin\Sliders\ManageSlidersRequest $request
     *
     * @return \App\Http\Responses\Admin\Sliders\CreateResponse
     */
    public function create()
    {
    	return view('admin.plans.create');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * @param \App\Http\Requests\Admin\Sliders\StoreSlidersRequest $request
     *
     * @return \App\Http\Responses\RedirectResponse
     */
    public function store(Request $request)
    {

    	$validatedData = $request->validate([
    		'name' => 'required|unique:membership_subscription_plans',
    		'interval' => 'required',
    		'amount' => 'required',
    		'amount' => 'required',
    	],[
    		'title.required' => 'Please Enter Plan Name',
    		'title.unique' => 'Duplicate Plan Name Not Allowed',
    		'interval.required' => 'Please Select Plan Interval',
    		'amount.required' => 'Plan Amount is Required',
    	]);

    	$input = $request->all();

    	Stripe\Stripe::setApiKey(config('services.stripe.secret'));

    	$interval_count=1;
    	$interval=$input['interval'];
    	if($input['interval']=='quarter'){
    		$interval_count=3;
    		$interval='month';
    	}
    	if($input['interval']=='semiannual'){
    		$interval_count=6;
    		$interval='month';
    	}
    	if(isset($input['trial_period_days'])){
    		$input['trial_period_days']=$input['trial_period_days'];
    	}else{
    		$input['trial_period_days']=0;
    	}

    	try {
    		$plan = \Stripe\Plan::create([
    			"amount" => $input['amount']*100.00,
    			"trial_period_days" =>$input['trial_period_days'],
    			"interval" => $interval,
    			"interval_count" => $interval_count,
    			"product" => [
    				"name" => $input['name']
    			],
    			"currency" => "gbp",
    			"id" => str_replace(' ', '-',strtolower($input['name']))
    		]);
    	} catch (\Stripe\Exception\RateLimitException $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	} catch (\Stripe\Exception\InvalidRequestException $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	} catch (\Stripe\Exception\AuthenticationException $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	} catch (\Stripe\Exception\ApiConnectionException $e) {
    		return redirect()->back()->with('error',$e->getMessage());    	
    	} catch (\Stripe\Exception\ApiErrorException $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	} catch (Exception $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	}
    	MembershipSubscriptionPlan::create([
    		"amount" => $input['amount'],
    		"interval" => $input['interval'],
    		"status" => $input['status'],
    		"name" => $input['name'],
    		"short_description" => $input['short_description'],
    		"description" => $input['description'],
    		"currency" => "gbp",
    		"plan_id" => str_replace(' ', '-',strtolower($input['name']))
    	]);

    	return redirect()->route('plans.index')
    	->with('success','Subscription Plans Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	$plans = MembershipSubscriptionPlan::find($id);

    	return view('admin.plans.edit',compact('plans'));
    }


     /**
     * @param \App\Http\Requests\Admin\Sliders\UpdateSlidersRequest $request
     *
     */
     public function update(Request $request, $id)
     {
     	$plans = MembershipSubscriptionPlan::find($id);
     	
     	$input = $request->all();

     	$plans->update([
     		"short_description" => $input['short_description'],
     		"description" => $input['description'],
     		"status" => $input['status'],
     	]);

     	return redirect()->route('plans.index')
     	->with('success','Subscription Plans Updated Successfully');
     }

    /**
     * Delete Sliders
     */
    public function destroy($id)
    {   
    	$plan = MembershipSubscriptionPlan::find($id);

    	Stripe\Stripe::setApiKey(config('services.stripe.secret'));

    	try {
    		$stripePlan = \Stripe\Plan::retrieve($plan->plan_id);
    		$product_id=$stripePlan->product;
    		$stripePlan->delete();
    	} catch (\Stripe\Exception\RateLimitException $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	} catch (\Stripe\Exception\InvalidRequestException $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	} catch (\Stripe\Exception\AuthenticationException $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	} catch (\Stripe\Exception\ApiConnectionException $e) {
    		return redirect()->back()->with('error',$e->getMessage());    	
    	} catch (\Stripe\Exception\ApiErrorException $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	} catch (Exception $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	}

    	try {
    		$product = \Stripe\Product::retrieve($product_id);
    		$product->delete();
    	} catch (\Stripe\Exception\RateLimitException $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	} catch (\Stripe\Exception\InvalidRequestException $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	} catch (\Stripe\Exception\AuthenticationException $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	} catch (\Stripe\Exception\ApiConnectionException $e) {
    		return redirect()->back()->with('error',$e->getMessage());    	
    	} catch (\Stripe\Exception\ApiErrorException $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	} catch (Exception $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	}

    	

    	$plan->delete();

    	return redirect()->route('plans.index')
    	->with('success','Subscription Plans Deleted Successfully');
    }

    public function importStripePlans()
    {
    	Stripe\Stripe::setApiKey(config('services.stripe.secret'));

    	try {
    		$plans=Stripe\Plan::all();
    		if(isset($plans) && count($plans)>0)
    		{
    			foreach ($plans as $key => $plan) {
    				$getPlan=MembershipSubscriptionPlan::where('plan_id',$plan['id'])->first();
    				if(!isset($getPlan)){

    					MembershipSubscriptionPlan::create([
    						"amount" => $plan['amount']/100,
    						"interval" => $plan['interval'],
    						"status" => $plan['active'],
    						"name" => str_replace(' ', '-',ucfirst($plan['id'])),
    					// "short_description" => $plan['short_description'],
    					// "description" => $plan['description'],
    						"currency" => "gbp",
    						"plan_id" => $plan['id']
    					]);
    				}
    			}

    			return redirect()->route('plans.index')
    			->with('success','Subscription Plans Created Successfully');
    		}else{
    			return redirect()->route('plans.index')
    			->with('error','Subscription Plans Not Available In Stripe Account');
    		}
    	} catch (\Stripe\Exception\RateLimitException $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	} catch (\Stripe\Exception\InvalidRequestException $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	} catch (\Stripe\Exception\AuthenticationException $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	} catch (\Stripe\Exception\ApiConnectionException $e) {
    		return redirect()->back()->with('error',$e->getMessage());      
    	} catch (\Stripe\Exception\ApiErrorException $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	} catch (Exception $e) {
    		return redirect()->back()->with('error',$e->getMessage());
    	}
    }
}