@extends('layouts.app')
@section('content')
    @include('include.header')
    <div class="main discount-page change-membership">
        <div class="custom-container">
            <div class="name-header">
                <h2>Change Membership</h2>
            </div>
            <div class="discount-wrap">
                @foreach($plans->data as $plan)
                    @if(strtolower(plan($plan->id)->name)!==Auth ::user() -> owner -> stripe_plan && plan($plan->id)->status==1 && $plan->interval=='month' &&
                    $plan->interval_count==1)
                        <div class="grid monthly">
                            <div class="shape-wrap">
                                <div class="heading">
                                    <h2>@if(isset(plan($plan->id)->name)){{ plan($plan->id)->name }} @endif</h2>
                                </div>
                                <div class="body">
                                    <div class="price-wrap">
                                        <span class="currency-symbol">£</span>
                                        <span class="price">{{ number_format($plan->amount/100, 2) }}</span>
                                        <span class="mo">/mo</span>
                                    </div>
                                    <h2 class="btn-wrap">@if(isset(plan($plan->id)->short_description)){!! trim(plan($plan->id)->short_description) !!}@endif</h2>
                                    <h3 class="billing">£{{ number_format($plan->amount/100, 2) }} billed on purchase</h3>
                                </div>
                            </div>
                            <div class="white-space">
                                <p>@if(isset(plan($plan->id)->description)){!! plan($plan->id)->description !!}@endif</p>
                                <div class="try-wrap">
                                    <a href="{{ route('change.plan',$plan->id) }}">Change Membership <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                @foreach($plans->data as $plan)
                    @if(strtolower(plan($plan->id)->name)!==Auth ::user() -> owner -> stripe_plan && plan($plan->id)->status==1 && $plan->interval=='year')
                        <div class="grid yearly most-popular">
                            <div class="shape-wrap">
                                <div class="corner-ribbon top-right sticky blue">Most Popular</div>
                                <div class="heading">
                                    <h2>@if(isset(plan($plan->id)->name)){{ plan($plan->id)->name }} @endif</h2>
                                </div>
                                <div class="body">
                                    <div class="price-wrap">
                                        <span class="currency-symbol">£</span>
                                        <span class="price">{{ number_format($plan->amount/100, 2) }}</span>
                                        <span class="mo">/Year</span>
                                    </div>
                                    <h2 class="btn-wrap">@if(isset(plan($plan->id)->short_description)){!! trim(plan($plan->id)->short_description) !!}@endif</h2>
                                    <h3 class="billing">£{{ number_format($plan->amount/100, 2) }} billed on purchase</h3>
                                </div>
                            </div>
                            <div class="white-space">
                                <p>@if(isset(plan($plan->id)->description)){!! plan($plan->id)->description !!}@endif</p>
                                <div class="try-wrap">
                                    <a href="{{ route('change.plan',$plan->id) }}">Change Membership <i class="fas fa-chevron-right"></i></a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                @foreach($plans->data as $plan)
                    @if(strtolower(plan($plan->id)->name)!==Auth ::user() -> owner -> stripe_plan && plan($plan->id)->status==1 && $plan->interval=='month' && $plan->interval_count==3)
                        <div class="grid quarterly">
                            <div class="shape-wrap">
                                <div class="heading">
                                    <h2>@if(isset(plan($plan->id)->name)){{ plan($plan->id)->name }} @endif</h2>
                                </div>
                                <div class="body">
                                    <div class="price-wrap">
                                        <span class="currency-symbol">£</span>
                                        <span class="price">{{ number_format($plan->amount/100, 2) }}</span>
                                        <span class="mo">/mo</span>
                                    </div>
                                    <h2 class="btn-wrap">@if(isset(plan($plan->id)->short_description)){!! trim(plan($plan->id)->short_description) !!}@endif</h2>
                                    <h3 class="billing">£{{ number_format($plan->amount/100, 2) }} billed on purchase</h3>
                                </div>
                            </div>
                            <div class="white-space">
                                <p>@if(isset(plan($plan->id)->description)){!! plan($plan->id)->description !!}@endif</p>
                                <div class="try-wrap">
                                    <a href="{{ route('change.plan',$plan->id) }}">Change Membership <i class="fas fa-chevron-right"></i></a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    @include('include.footer')
@endsection
