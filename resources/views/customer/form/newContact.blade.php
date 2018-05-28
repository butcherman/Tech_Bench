<div class="d-none">
    <div class="row form-group justify-content-center" id="number-type-div">
        <div class="col-3">            
            {{ Form::select('numType[]', $numberTypes, 2, ['class' => 'form-control']) }}
        </div>
        <div class="col-6">
            {{ Form::tel('phoneNumber[]', null, ['class' => 'form-control', 'placeholder' => 'Phone Number']) }}
        </div>
        <div class="col-3">
            {{ Form::text('extension[]', null, ['class' => 'form-control', 'placeholder' => 'ext']) }}
        </div>
    </div>
</div>

{!! Form::open(['route' => 'customer.contacts.store', 'id' => 'new-contact-form']) !!}
    {{ Form::hidden('custID', null, ['id' => 'custID']) }}
    {{ Form::bsText('name', 'Contact Name', null, ['required']) }}
    {{ Form::bsEmail('email', 'Email Address') }}
    <fieldset class="form-group" id="phone-numbers">
        <legend>Phone Numbers</legend>
        <div class="row form-group justify-content-center">
            <div class="col-3">            
                {{ Form::select('numType[]', $numberTypes, 2, ['class' => 'form-control']) }}
            </div>
            <div class="col-6">
                {{ Form::tel('phoneNumber[]', null, ['class' => 'form-control', 'placeholder' => 'Phone Number']) }}
            </div>
            <div class="col-3">
                {{ Form::text('extension[]', null, ['class' => 'form-control', 'placeholder' => 'ext']) }}
            </div>
        </div>
        
    </fieldset>
    <div class="row justify-content-center">
        <div class="col-4">
            <button class="btn btn-block btn-info pad-bottom" id="add-number">Add Number</button>
        </div>
    </div>
    {{ Form::bsSubmit('Add Contact') }}
{!! Form::close() !!}
