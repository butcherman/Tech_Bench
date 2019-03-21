<div class="form-group">
    {{ Form::label('timezone', 'Timezone:', ['class' => 'control-label']) }}
    {!! Timezonelist::create('timezone', Config('app.timezone'), 'id="timezone" class="form-control"') !!}
    @if($errors->has($name))
        <span class="invalid-feedback d-inline">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</div>
