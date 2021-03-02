<div class="col-md-6">
    <div class="form-group">
        <label for="user_id">Determination</label>
        {{ Form::text('loneliness', $personality->loneliness, ['class' => 'form-control']) }}
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label for="user_id">Genre Flexibility</label>
        {{ Form::text('entertainment', $personality->entertainment, ['class' => 'form-control']) }}
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label for="user_id">Communication</label>
        {{ Form::text('curiosity', $personality->curiosity  , ['class' => 'form-control']) }}
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label for="user_id">Work Ethic</label>
        {{ Form::text('relationship', $personality->relationship  , ['class' => 'form-control']) }}
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label for="user_id">Honesty</label>
        {{ Form::text('hookup', $personality->hookup  , ['class' => 'form-control']) }}
    </div>
</div>
