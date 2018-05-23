<div class="form-group">
    {{ Form::label(str_replace(' ', '_', $name), $label.':', ['class' => 'control-label']) }}
    {{ Form::number($name, $value, array_merge(['class' => 'form-control', 'id' => str_replace(' ', '_', $name)], $attributes)) }}
</div>
