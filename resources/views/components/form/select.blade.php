<div class="form-group">
    {{ Form::label($name, $label.':', ['class' => 'control-label']) }}
    {{ Form::select($name, $list, $selected, array_merge(['class' => 'form-control'], $attributes)) }}
    @if($errors->has($name))
        <span class="invalid-feedback">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</div>
