<div class="form-group">
    {{ Form::label('timezone', 'Timezone:', ['class' => 'control-label']) }}
    {!! Timezonelist::create('timezone', Config('app.timezone'), 'id="timezone" class="form-control"') !!}
</div>
