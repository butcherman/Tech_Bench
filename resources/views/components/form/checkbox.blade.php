<div class="form-check">
    {{ Form::checkbox($name, $value, $checked, array_merge(['class' => 'form-check-input', 'id' => $name], $attributes)) }}
    {{ Form::label($name, $label, ['class' => 'control-label']) }}
</div>
