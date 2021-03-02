<div class="box-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('competitions', 'Select Competitions', ['class' => 'control-label']) }}
                {{ Form::select('competitions', [""=>"Select Competitions","Writing competitions"=>"Writing competitions","Filming competitions"=>"Filming competitions","Singing competitions"=>"Singing competitions","Best photo competitions"=>"Best photo competitions"],null, ['class' => 'form-control']) }}
                @if ($errors->has('competitions'))
                    <span class="text-danger">{{ $errors->first('competitions') }}</span>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('title','Enter Title', ['class' => 'control-label required']) }}
                {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Title']) }}
                @if ($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
            </div>
        </div>
        {{--        <div class="col-md-3">--}}
        {{--            {{ Form::label('country', 'Select Country', ['class' => 'control-label']) }}--}}
        {{--            <select name="country" id="countryCareer" class="form-control select2">--}}
        {{--                <option value=''>- Search Country -</option>--}}
        {{--            </select>--}}
        {{--            @if ($errors->has('country'))--}}
        {{--                <span class="text-danger">{{ $errors->first('country') }}</span>--}}
        {{--            @endif--}}
        {{--        </div>--}}
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('status', 'Status', ['class' =>'control-label required']) }}
                <br/>
                {{ Form::radio('status', 1, true) }} Active
                {{ Form::radio('status',0,false) }} In Active
                @if(isset($discover) && $discover->status==2)
                    {{ Form::radio('status',2,true) }} Result Declare
                @endif
                @if ($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('icon', 'Discover Icon', ['class' => 'control-label required']) }}
                @if(!empty($discover->icon))
                    <input type="file" name="icon" id="icon-1" class="inputfile inputfile-1"/>
                    <br/>
                    <img src="{{ asset('public/img/discover/'.$discover->icon) }}" width="80" height="80"
                         id="discover_img">
                @else
                    <input type="file" name="icon" id="icon-1" class="inputfile inputfile-1"/>
                @endif
                @if ($errors->has('icon'))
                    <span class="text-danger">{{ $errors->first('icon') }}</span>
                @endif

                @include('admin.career.image-crop-wrap')

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('description',"Description", ['class' => 'control-label required']) }}
                {{ Form::textarea('description', null, ['class' => 'form-control',"id"=>"discover_description", 'placeholder' => "Description"]) }}
                @if ($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
            </div>
        </div>
    </div>
</div>
