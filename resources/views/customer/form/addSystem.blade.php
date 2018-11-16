@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <h5 class="text-center">Error: {{ $error }}</h5>
        @endforeach
    </div>
@endif
<div id="show-duplicates" class="alert alert-danger">
    <h5>Customer Already Has This System</h5>
</div>
{!! Form::open(['route' => 'customer.systems.store', 'id' => 'new-system-form']) !!}
    {{ Form::hidden('cust_id', null, ['id' => 'cust_id']) }}
    {{ Form::bsSelect('sysType', 'Select System', $systems, null, ['placeholder' => 'Select A System']) }}
    <div id="system-details"></div>
    {{ Form::bsSubmit('Add System') }}
{!! Form::close() !!}
