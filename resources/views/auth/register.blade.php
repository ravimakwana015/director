@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">{{ __('Register') }}</div>

				<div class="card-body">
					@if (session('status'))
					<div class="alert alert-success" role="alert">
						{{ session('status') }}
					</div>
					@endif
					{{ Form::open(['route' => 'register','role' => 'form', 'method' => 'post', 'id' => 'create-cate', 'files' => true]) }}
					@if(isset($link['code']))
					<input type="hidden" name="code" value="{{ $link['code'] }}">
					@endif
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								{{ Form::label('username','User Name', ['class' => 'control-label required']) }}
								{{ Form::text('username', null, ['class' => 'form-control', 'placeholder' => 'User Name']) }}
								@if ($errors->has('username'))
								<span class="text-danger">{{ $errors->first('username') }}</span>
								@endif
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								{{ Form::label('first_name','First Name', ['class' => 'control-label required']) }}
								{{ Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'First Name']) }}
								@if ($errors->has('first_name'))
								<span class="text-danger">{{ $errors->first('first_name') }}</span>
								@endif
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								{{ Form::label('last_name','Last Name', ['class' => 'control-label required']) }}
								{{ Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Last Name']) }}
								@if ($errors->has('last_name'))
								<span class="text-danger">{{ $errors->first('last_name') }}</span>
								@endif
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								{{ Form::label('city','City', ['class' => 'control-label required']) }}
								{{ Form::text('city', null, ['class' => 'form-control', 'placeholder' => 'City']) }}
								@if ($errors->has('city'))
								<span class="text-danger">{{ $errors->first('city') }}</span>
								@endif
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								{{ Form::label('email','Email', ['class' => 'control-label required']) }}
								{{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) }}
								@if ($errors->has('email'))
								<span class="text-danger">{{ $errors->first('email') }}</span>
								@endif
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								{{ Form::label('mobile','Mobile', ['class' => 'control-label required']) }}
								{{ Form::text('mobile', null, ['class' => 'form-control', 'placeholder' => 'Mobile']) }}
								@if ($errors->has('mobile'))
								<span class="text-danger">{{ $errors->first('mobile') }}</span>
								@endif
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								{{ Form::label('password','Password', ['class' => 'control-label required']) }}
								{{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) }}
								@if ($errors->has('password'))
								<span class="text-danger">{{ $errors->first('password') }}</span>
								@endif
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								{{ Form::label('confirm_password','Confirm Password', ['class' => 'control-label required']) }}
								{{ Form::password('confirm_password', ['class' => 'form-control', 'placeholder' => 'Confirm Password']) }}
								@if ($errors->has('confirm_password'))
								<span class="text-danger">{{ $errors->first('confirm_password') }}</span>
								@endif
							</div>
						</div>
					</div>
					<div class="row">
                           {{--  <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('status', 'Status', ['class' =>'control-label required']) }}
                                    {{ Form::radio('status', 1, ['class' => 'form-control', 'placeholder' => 'Status']) }} Active
                                    {{ Form::radio('status',0, ['class' => 'form-control', 'placeholder' => 'Status']) }} In Active
                                </div>

                            </div> --}}
                            <div class="col-md-6">
                            	<div class="form-group">
                            		{{ Form::label('user_type', 'Select User Type', ['class' => 'control-label']) }}
                            		{{ Form::select('user_type', [""=>"Select Actor","Actor"=>"Actor","Actress"=>"Actress","Directors"=>"Directors"],null, ['class' => 'form-control']) }}
                            		@if ($errors->has('user_type'))
                            		<span class="text-danger">{{ $errors->first('user_type') }}</span>
                            		@endif
                            	</div>
                            </div>
                            <div class="col-md-6">
                            	<div class="form-group">
                            		{{ Form::label('skills', 'Enter Your Skill', ['class' => 'control-label']) }}
                            		{{ Form::text('skills',null, ['class' => 'form-control']) }}
                            		@if ($errors->has('skills'))
                            		<span class="text-danger">{{ $errors->first('skills') }}</span>
                            		@endif
                            	</div>
                            </div>
                        </div>
                        <div class="row">
                        	<div class="col-md-6">
                        		<div class="form-group">
                        			{{ Form::label('profile_description',"Profile Description", ['class' => 'control-label required']) }}
                        			{{ Form::textarea('profile_description', null, ['class' => 'form-control', 'placeholder' => "Profile Description"]) }}
                        			@if ($errors->has('profile_description'))
                        			<span class="text-danger">{{ $errors->first('profile_description') }}</span>
                        			@endif
                        		</div>
                        	</div>
                        	<div class="col-md-6">
                        		<div class="form-group">
                        			{{ Form::label('profile_picture', 'Profile Picture', ['class' => 'control-label required']) }}
                        			<input type="file" name="profile_picture" id="profile_picture-1" class="inputfile inputfile-1"  />
                        		</div>
                        	</div>
                        </div>

                        <div class="form-group row mb-0">
                        	<div class="col-md-6 offset-md-4">
                        		<button type="submit" class="btn btn-primary">
                        			{{ __('Register') }}
                        		</button>
                        	</div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
