@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <h5 class="text-center">Error: {{ $error }}</h5>
        @endforeach
    </div>
@endif
{!! Form::model($cust, ['route' => ['customer.id.update', $cust->cust_id]]) !!}
    @method('PUT')
    {{ Form::bsText('name', 'Customer Name', null, ['required']) }}
    {{ Form::bsText('dba_name', 'DBA Name/AKA', null) }}
    {{ Form::bsText('address', 'Address', null, ['required']) }}
    {{ Form::bsText('city', 'City', null, ['required']) }}
    <div class="row">
        <div class="col-6">
            {{ Form::allStates(null) }}
        </div>
        <div class="col-6">
            {{ Form::bsNumber('zip', 'Zip Code', null, ['maxlength' => 5, 'minlength' => 5, 'required']) }}
        </div>
    </div>
    {{ Form::bsSubmit('Update Customer') }}
{!! Form::close() !!}
