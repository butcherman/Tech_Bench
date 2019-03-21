<div class="form-check">
    {{ Form::checkbox($name, $value, $checked, array_merge(['class' => 'form-check-input', 'id' => $name], $attributes)) }}
    {{ Form::label($name, $label, ['class' => 'control-label']) }}
    @if($errors->has($name))
        <span class="invalid-feedback d-inline">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</div>
