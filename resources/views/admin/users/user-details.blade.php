	<div class="custom-container">
		<div class="profile-wrap @if($user->user_type=='1') actor @endif @if($user->user_type=='2') model @endif @if($user->user_type=='3') musician @endif">
			<div class="panel panel-primary">
				<div class="panel-heading"><b>Caption</b></div>
				<div class="panel-body">
					@if(isset($user->caption) && !empty($user->caption))
					{!! $user->caption  !!}
					@else N/A  @endif
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading"><b>Experience</b></div>
				<div class="panel-body">
					@if(isset($user->experience) && !empty($user->experience))
					{!! $user->experience  !!}
					@else N/A  @endif
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading"><b>Netflix Shows</b></div>
				<div class="panel-body">
					@if(isset($user->top_shows) && !empty($user->top_shows))
					<ul class="favourite-wrap">
						@foreach(json_decode($user->top_shows) as $show)
						<li>
							<a href="@if(isset($show->link)){{ $show->link }}@endif" target="_blank">@if(isset($show->name)){!! $show->name !!}@endif</a>
						</li>
						@endforeach
					</ul>
					@else N/A  @endif
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading"><b>Top 3 favourite Producers</b></div>
				<div class="panel-body">
					@if(isset($user->favourite_films) && !empty($user->favourite_films))
					<ul class="favourite-wrap">
						@foreach(json_decode($user->favourite_films) as $films)
						<li><a href="@if(isset($films->link)){{ $films->link }}@endif" target="_blank">@if(isset($films->name)){!! $films->name !!}@endif</a></li>
						@endforeach
					</ul>
					@else N/A  @endif
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading"><b>Top 3 Favourite songs</b></div>
				<div class="panel-body">
					@if(isset($user->top_songs) && !empty($user->top_songs))
					<ul class="favourite-wrap">
						@foreach(json_decode($user->top_songs) as $song)
						<li><a href="@if(isset($song->link)){{ $song->link }}@endif" target="_blank">@if(isset($song->name)){!! $song->name !!}@endif</a></li>
						@endforeach
					</ul>
					@else N/A  @endif
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading"><b>Biography</b></div>
				<div class="panel-body">
					@if(isset($user->biography) && !empty($user->biography))
					{{ $user->biography }}
					@else N/A  @endif
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading"><b>Goals</b></div>
				<div class="panel-body">
					@if(isset($user->goals) && !empty($user->goals))
					{{ $user->goals }}
					@else N/A  @endif
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading"><b>Interests</b></div>
				<div class="panel-body">
					@if(isset($user->interests) && !empty($user->interests))
					{{ $user->interests }}
					@else N/A  @endif
				</div>
			</div>
		</div>
		<div class="profile-wrap @if($user->user_type=='1') actor @endif @if($user->user_type=='2') model @endif @if($user->user_type=='3') musician @endif">
			<div class="grid">
				<div class="box">
					@php
					$stars=json_decode($user->personality);

					@endphp
					<h3 class="title">Stars</h3>
					<div class="progress-bar-wrap">

						<div class="p-bar">
							<h4>Determination</h4>
							<div class="progress">
								<div class="progress-bar" role="progressbar" aria-valuenow="@if(isset($stars->loneliness)){{ $stars->loneliness/2 }}@else 0 @endif" aria-valuemax="100" @if(isset($stars->loneliness)) style="width: {{ $stars->loneliness/2 }}%;" @else style="width: 0%;" @endif></div>
							</div>
						</div>
						<div class="p-bar">
							<h4>Genre Flexibility</h4>
							<div class="progress">
								<div class="progress-bar" role="progressbar" aria-valuenow="@if(isset($stars->entertainment)) {{ $stars->entertainment/2 }} @else 0 @endif" aria-valuemax="100" @if(isset($stars->entertainment)) style="width: {{ $stars->entertainment/2 }}%;" @else style="width: 0%;" @endif></div>
							</div>
						</div>
						<div class="p-bar">
							<h4>Communication</h4>
							<div class="progress">
								<div class="progress-bar" role="progressbar" aria-valuenow="@if(isset($stars->curiosity)) {{ $stars->curiosity/2 }} @else 0 @endif" aria-valuemax="100" @if(isset($stars->curiosity)) style="width: {{ $stars->curiosity/2 }}%;" @else style="width: 0%;" @endif></div>
							</div>
						</div>
						<div class="p-bar">
							<h4>Work Ethic</h4>
							<div class="progress">
								<div class="progress-bar" role="progressbar" aria-valuenow="@if(isset($stars->relationship)) {{ $stars->relationship/2 }} @else 0 @endif" aria-valuemax="100" @if(isset($stars->relationship)) style="width: {{ $stars->relationship/2 }}%;" @else style="width: 0%;" @endif></div>
							</div>
						</div>
						<div class="p-bar">
							<h4>Honesty</h4>
							<div class="progress">
								<div class="progress-bar" role="progressbar" aria-valuenow="@if(isset($stars->hookup)) {{ $stars->hookup/2 }} @else 0 @endif" aria-valuemax="100" @if(isset($stars->hookup)) style="width: {{ $stars->hookup/2 }}%;" @else style="width: 0%;" @endif></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
