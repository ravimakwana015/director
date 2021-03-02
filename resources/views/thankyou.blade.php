@extends('layouts.app')
<?php
if (count(generalpage()) > 0) {
    $generalpage = generalpage()[0];
}
?>
@if(count(generalpage())>0)
    @section('meta-title',strip_tags($generalpage['seo_title']))
@section('meta-keywords',strip_tags($generalpage['seo_keyword']))
@section('meta-description',strip_tags($generalpage['seo_description']))
@endif
@section('content')
    @include('include.header')

    <div class="main dashboard-page">
        <div class="custom-container">
            <div class="thankyou-wrap">
                <div class="my-account">
                    <div class="account-header custom-container">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif
                        <h3>Thank You For Your Subscription</h3>
                    </div>
                    <div class="account-body">
                        <div class="account">
                            <div class="text">
                                <h4>Your Active Membership</h4>
                                @if(isset($plan))
                                    <span>{{ plan($plan->id)->name }} - (£{{ number_format($plan->amount/100, 2) }} Every {{ plan($plan->id)->name }})</span>
                                @endif
                                @if(isset($customPlan))
                                    <span>{{ $customPlan->stripe_plan }} - (£0 every {{ $customPlan->stripe_plan }})</span>
                                @endif
                            </div>
                            <div class="btn-wrap">
                                <a href="{{ route('dashboard') }}" class="btn btn-red">Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
