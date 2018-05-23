<div class="form-check">
    {{ Form::checkbox($name, $value, $checked, ['class' => 'form-check-input', 'id' => $name]) }}
    {{ Form::label($name, $label, ['class' => 'control-label']) }}
</div>
