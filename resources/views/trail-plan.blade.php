@extends('layouts.app')

@section('content')
    <div class="main-content contact-page payment-page">
        <div class="logo">
            <a href="{{ route('home') }}">PRODUCERS EYE</a>
        </div>
        <div class="contact-us">
            <div class="left-content">
                <div class="contact-text-wrap">
                    <p>To Pay</p>
                    <h2>£{{ number_format($plan->amount/100, 2) }}</h2>
                </div>
                <div class="back-btn-wrap">
                    <a href="{{ route('dashboard') }}"><i class="fas fa-caret-left"></i> Cancel your payment</a>
                </div>
            </div>
            <div class="right-content">
                <div class="contact-form-wrap">
                    <h2>Payments</h2>
                    <div class="pay-with">
                        <span>Pay with credit card</span>
                        <img src="{{ asset('public/front/images/visa.png')}}" alt="visa">
                    </div>
                    {!! Form::open(['url' => route('trail-order-post'), 'data-parsley-validate', 'id' => 'payment-form']) !!}
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    <div id="error-msg"></div>
                    <input type="hidden" value="{{ $plan->id }}" name="plan">

                    <div class="form-item" id="cc-group">
                        <label>Credit Card Number</label>
                        {!! Form::text('number', null, [
                            'class'                         => 'form-control',
                            'required'                      => 'required',
                            'data-stripe'                   => 'number',
                            'data-parsley-type'             => 'number',
                            'maxlength'                     => '16',
                            'data-parsley-trigger'          => 'change focusout',
                            'data-parsley-class-handler'    => '#cc-group'
                            ]) !!}
                    </div>
                    <div class="form-item" id="ccv-group">
                        <label>Expiration</label>
                        {!! Form::selectMonth('exp_month', null, [
                            'required'              => 'required',
                            'data-stripe'           => 'exp-month'
                            ], '%m') !!}
                        {!! Form::selectYear('exp_year', date('Y'), date('Y') + 10, null, [
                            'required'          => 'required',
                            'data-stripe'       => 'exp-year'
                            ]) !!}
                    </div>
                    <div class="form-item">
                        <label>CVC/CVV</label>
                        <input required="required" data-stripe="cvc" data-parsley-type="number" data-parsley-trigger="change focusout" maxlength="4"
                               data-parsley-class-handler="#ccv-group" placeholder="3 or 4 digits code" name="cvc" type="password"/>

                    </div>
                    <div class="form-action">
                        {!! Form::submit('Place order!', ['class' => 'btn', 'id' => 'submitBtn', 'style' => 'margin-bottom: 10px;']) !!}
                    </div>
                   {!! Form::close() !!}
                </div>
            </div>
        </div>

        <!-- PARSLEY -->
        <script>
            window.ParsleyConfig = {
                errorsWrapper: '<div></div>',
                errorTemplate: '<div class="alert alert-danger parsley" role="alert"></div>',
                errorClass: 'has-error',
                successClass: 'has-success'
            };
        </script>

        <script src="https://parsleyjs.org/dist/parsley.js"></script>
        <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
        <script>
            Stripe.setPublishableKey("<?php echo env('STRIPE_KEY') ?>");
        </script>
        <script src="{{ asset('public/front/js/discount.js?v=4.0.0') }}"></script>
    </div>
@endsection

