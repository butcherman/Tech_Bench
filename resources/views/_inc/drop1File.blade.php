<div id="box-filedrag">
    <i class="fa fa-upload" aria-hidden="true"></i>
    {{ Form::file('file', ['id' => 'fileselect', 'data-multiple-caption', '{count} files selected']) }}
    {{ Form::label('fileselect', 'Select A File:', ['id' => 'box-dragndrop']) }}
    <span id="dragndrop-notice">Or Drag It Here</span>
</div>
