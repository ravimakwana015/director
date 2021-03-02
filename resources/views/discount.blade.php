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
                    @if(isset(plan($plan->id)->name) && !empty(plan($plan->id)->name))
                        <h2>{{ plan($plan->id)->name }} - £{{ number_format($plan->amount/100, 2) }} every {{ $plan->interval_count }} {{ $plan->interval }}</h2>
                    @endif
                </div>
                <div class="back-btn-wrap">
                    <a href="{{ route('plans') }}"><i class="fas fa-caret-left"></i> Cancel</a>
                </div>
            </div>
            <div class="right-content">
                <div class="contact-form-wrap">
                    <h2>Choose Payments</h2>
                    <form id="coupon-form">
                        <div id="coupon_message"></div>
                        <div id="valid_message"></div>
                        @if(isset($plan) && !empty($plan))
                            <input type="hidden" value="{{ $plan->id }}" name="plan">
                        @endif
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class='form-group cvc required'>
                                    <label class='control-label'>Coupon Code</label>
                                    <input autocomplete='off' class='form-control' placeholder='Coupon code' id="coupon_code" name="coupon" type='text'>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <button type="button" class="btn" id="pay">Apply Coupon</button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-warning" id="skip">SKIP</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('after-scripts')
    @if(isset(plan($plan->id)->name) && !empty(plan($plan->id)->name))
        <script>
            var config = {
                routes: {
                    get_payment: '{{ route('get.payment',$plan->id) }}',
                    get_checkcoupon: '{{ route('get.checkcoupon') }}',
                    plan_name: '{{ plan($plan->id)->name }}',
                    plan_id: '{{ $plan->id }}',
                    thankyou: '{{ route('thankyou') }}',
                }
            };
        </script>
        <script src="{{ asset('public/front/js/discount.js?v=4.0.0') }}"></script>
    @endif
@endsection
