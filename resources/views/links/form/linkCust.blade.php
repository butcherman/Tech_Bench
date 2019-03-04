{!! Form::open(['id' => 'new-customer-link-form']) !!}
    <div class="form-group">
        <label for="customer-tag">Link to Customer:</label>
        <div class="input-group">
            <input class="form-control border-right-0 border" type="search" id="customer-tag" name="customer_tag" placeholder="Enter Customer Number or Click Search Icon (Optional)" autocomplete="off" value="{{$cust}}" />
            <span class="input-group-append" id="search-for-customer">
                <button class="btn btn-outline-secondary border-left-0 border" id="search-for-customer-button" type="button" tabindex="-1">
                <i class="fa fa-search"></i>
            </button>
            </span>
        </div>
    </div>
    {{ Form::bsSubmit('Attach Customer') }}
{!! Form::close() !!}
<div id="cust-list-append"></div>
