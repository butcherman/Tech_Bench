<div class="form-group">
    {{ Form::label(str_replace(' ', '_', $name), $label.':', ['class' => 'control-label']) }}
    {{ Form::textarea($name, $value, array_merge(['class' => 'form-control', 'id' => str_replace(' ', '_', $name)], $attributes)) }}
    @if($errors->has($name))
        <span class="invalid-feedback d-inline">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</div>
