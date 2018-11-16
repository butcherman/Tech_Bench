<div class="form-group">
    {{ Form::label($name, $label.':', ['class' => 'control-label']) }}
    {{ Form::select($name, $list, $selected, array_merge(['class' => 'form-control'], $attributes)) }}
</div>
