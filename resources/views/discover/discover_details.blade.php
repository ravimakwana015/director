<div class="main-content discover-details contact-page main">
    <div class="contact-us">
        <div class="left-content">
            <div class="contact-form-wrap">
                <div class="contact-text-wrap">
                    <div class="contact-info">
                        <h2>{!! $discover->title !!}</h2>
                    </div>
                </div>
                <h2>Apply using the form below</h2>
                @include('admin.include.message')
                <form method="POST" action="{{ route('discover.application') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="discover_id" value="{{ $discover->id }}">
                    <div class="form-item">
                        {{ Form::textarea('cover_letter', null, ['placeholder' => "Additional Comments",'rows'=>'2']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('cv', 'Enter your Work', ['required']) }}
                        <label class="custom-file">
                            <input type="file" name="cv" id="cv-1" class="custom-file-input">
                            <span class="custom-file-control">Choose file</span>
                        </label>
                    </div>
                    <div class="form-action">
                        <button type="submit" class="btn">
                            {{ __('Submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="right-content">
            <div class="contact-text-wrap">
                <div class="contact-info">
                    <h2>{!! $discover->title !!}</h2>
                    {!! $discover->description !!}
                </div>
            </div>
        </div>
    </div>
</div>


