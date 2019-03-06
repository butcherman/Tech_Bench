<div class="form-group">
    {{ Form::label(str_replace(' ', '_', $name), $label.':', ['class' => 'control-label']) }}
    {{ Form::text($name, $value, array_merge(['class' => 'form-control', 'id' => str_replace(' ', '_', $name)], $attributes)) }}
    @if($errors->has($name))
        <span class="invalid-feedback">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</div>
