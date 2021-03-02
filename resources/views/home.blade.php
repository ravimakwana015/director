@extends('layouts.app')

@section('content')
@include('include.header')

<div class="main home-page">
	<section class="bg-img-wrap">
		@foreach($settings as $settingsvalue)
		<div class="default-img img-wrap active">
			<div class="img-overlay"></div>
			@if(isset($settingsvalue->default_image) && !empty($settingsvalue->default_image))
			<img src="{{ asset('public/img/logo/'.$settingsvalue->default_image.'') }}" alt="bg-main">
			@else
			<img src="{{ asset('public/front/images/bg-main.jpg') }}" alt="bg-main">
			@endif
		</div>
		@endforeach

		@foreach($slider as $sliders)
		<div id="{{ $sliders['title'] }}" class="img-wrap">
			<div class="img-overlay"></div>
			<img src="{{ asset('public/img/sliders/'.$sliders['image'].'') }}" alt="actors-bg">
		</div>
		@endforeach
	</section>
	<section class="toggle-content">
		@foreach($settings as $settingsvalue)
		<p class="slide-text">{{ $settingsvalue->slider_tagline }}</p>
		@endforeach
		<div class="nav-item">
			@foreach(array_chunk($slider, 2) as $key=>$sliders)
			@if($key==0)
			<div class="slide-1">
				@foreach($sliders as $slid)
				<h2>
					<a href="javascript:;" data-target="{{ $slid['title'] }}" onclick="browse('{{ $slid['title'] }}','{{ route('transfer') }}','{{route('users')}}')" class="slide-text">{{ $slid["title"] }}</a>
				</h2>
				@endforeach
			</div>
			@else
			<div class="slide-2">
				@foreach($sliders as $slid)
				<h2><a href="javascript:;" data-target="{{ $slid['title'] }}" onclick="browse('{{ $slid['title'] }}','{{ route('transfer') }}','{{route('users')}}')" class="slide-text">{{ $slid["title"] }}</a></h2>
				@endforeach
			</div>
			@endif
			@endforeach
		</div>
	</section>
</div>
@endsection
@section('after-scripts')
@endsection
