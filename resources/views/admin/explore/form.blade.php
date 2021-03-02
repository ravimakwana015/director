<div class="box-body">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                {{ Form::label('title','Job Title', ['class' => 'control-label required']) }}
                {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Job Title']) }}
                @if ($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('location','Job Location', ['class' => 'control-label required']) }}
                {{ Form::text('location', null, ['class' => 'form-control', 'placeholder' => 'Job location']) }}
                @if ($errors->has('location'))
                    <span class="text-danger">{{ $errors->first('location') }}</span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            {{ Form::label('country', 'Select Country', ['class' => 'control-label']) }}
            <select name="country" id="countryDevelop" class="form-control select2">
                <option value=''>- Search Country -</option>
            </select>
            @if ($errors->has('country'))
                <span class="text-danger">{{ $errors->first('country') }}</span>
            @endif
        </div>
    </div>
    <div class="row">
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
                {{ Form::label('icon', 'Job Icon', ['class' => 'control-label required']) }}
                @if(!empty($explore->icon))
                    <input type="file" name="icon" id="icon-1" class="inputfile inputfile-1"/>
                    <br/>
                    <img src="{{ asset('public/img/explore/' . $explore->icon) }}" width="80" height="80" id="explore_img">
                @else
                    <input type="file" name="icon" id="icon-1" class="inputfile inputfile-1"/>
                @endif
                @if ($errors->has('icon'))
                    <span class="text-danger">{{ $errors->first('icon') }}</span>
                @endif
                @include('admin.career.image-crop-wrap')
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('job_type', 'Select Job Type', ['class' => 'control-label']) }}
                {{ Form::select('job_type', [""=>"Select Job Type","Actor"=>"Actor","Models"=>"Models","Musicians"=>"Musicians"],null, ['class' => 'form-control']) }}
                @if ($errors->has('job_type'))
                    <span class="text-danger">{{ $errors->first('job_type') }}</span>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('workshop_image', 'workshop Image', ['class' => 'control-label required']) }}
                @if(!empty($explore->workshop_image))
                    <input type="file" name="workshop_image" id="workshop_image-1" class="inputfile inputfile-1"/>
                    <br/>
                    <img src="{{ asset('public/img/explore/' . $explore->workshop_image) }}" width="80" height="80" id="explore_img">
                @else
                    <input type="file" name="workshop_image" id="workshop_image-1" class="inputfile inputfile-1"/>
                @endif
                @if ($errors->has('workshop_image'))
                    <span class="text-danger">{{ $errors->first('workshop_image') }}</span>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('link','Workshop Link', ['class' => 'control-label required']) }}
                {{ Form::text('link', null, ['class' => 'form-control', 'placeholder' => 'Job link']) }}
                @if ($errors->has('link'))
                    <span class="text-danger">{{ $errors->first('link') }}</span>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('description',"Description", ['class' => 'control-label required']) }}
                {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => "Description","id"=>"career_description"]) }}
                @if ($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
            </div>
        </div>
    </div>
</div>
