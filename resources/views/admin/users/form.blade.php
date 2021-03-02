<div class="box-body">
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
                {{ Form::label('email','Email', ['class' => 'control-label required']) }}
                {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) }}
                @if ($errors->has('email'))
                <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('status', 'Status', ['class' =>'control-label required']) }}
                <br/>
                {{ Form::radio('status', 1, true) }} Active
                {{ Form::radio('status',0, false) }} In Active
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('user_type', 'Select User Type', ['class' => 'control-label']) }}
                {{ Form::select('user_type', ["1"=>"Actor","2"=>"Model","3"=>"Musician","4"=>"Creator"],null, ['class' => 'form-control']) }}
                @if ($errors->has('user_type'))
                <span class="text-danger">{{ $errors->first('user_type') }}</span>
                @endif
            </div>
        </div>
    </div>
    @if(!isset($users))
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
    @endif
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('profile_picture', 'Profile Picture', ['class' => 'control-label required']) }}
                @if(!empty($users->profile_picture))
                <input type="file" name="profile_picture" id="profile_picture-1" class="inputfile inputfile-1"/>
                <br/>
                <img src="{{ asset('public/img/profile_picture/' . $users->profile_picture) }}" width="80" height="80" id="career_img">
                @else
                <input type="file" name="profile_picture" id="profile_picture-1" class="inputfile inputfile-1"/>
                @endif

                @include('admin.career.image-crop-wrap')
            </div>
            <span class="text-danger" id="image-dimension"></span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('private', 'Private User', ['class' =>'control-label required']) }}
                <br/>
                {{ Form::radio('private_user', 0, true) }} Normal
                {{ Form::radio('private_user',1, false) }} Private
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('Verify User', 'Verify User', ['class' =>'control-label required']) }}
                <br/>
                {{ Form::checkbox('verify', 'verify') }} verify
                @if ($errors->has('verify'))
                <span class="text-danger">{{ $errors->first('verify') }}</span>
                @endif
            </div>
        </div>
        {{-- <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('profile_description',"Profile Description", ['class' => 'control-label required']) }}
                {{ Form::textarea('profile_description', null, ['class' => 'form-control', 'placeholder' => "Profile Description"]) }}
                @if ($errors->has('profile_description'))
                <span class="text-danger">{{ $errors->first('profile_description') }}</span>
                @endif
            </div>
        </div> --}}
    </div>
</div>
