{!! Form::open(['route' => 'customer.search', 'id' => 'search-customer-form']) !!}
    <div class="form-group row justify-content-center">
        <div class="col-md-4">
            {{ Form::text('customer', '', ['class' => 'form-control', 'id' => 'search-name', 'Placeholder' => 'Search Customer by Name', 'autofocus']) }}
        </div>
        <div class="col-md-3 mt-1 mt-sm-0">
            {{ Form::text('city', '', ['class' => 'form-control', 'id' => 'search-city', 'placeholder' => 'Search Customer by City']) }}
        </div>
        <div class="col-md-3 mt-1 mt-sm-0">
            {{ Form::select('system', $systems, null, ['class' => 'form-control', 'id' => 'search-system', 'placeholder' => 'Search by System']) }}
        </div>
        <div class="col-md-2 mt-1 mt-sm-0">
            <div class="row justify-content-center">
                <div class="col-6">
                    {{ Form::button('<i class="fa fa-search" aria-hidden="true"></i>', ['class' => 'form-control btn btn-primary', 'title' => 'Search For Customer', 'data-toggle' => 'tooltip', 'type' => 'submit']) }}
                </div>
                <div class="col-3 d-none d-sm-block">
                    <a href="{{route('customer.id.create')}}" title="New Customer" class="btn btn-primary" data-toggle="tooltip"><i class="fa fa-plus" aria-hidden="true"></i></a>
                </div>
                <div class="col-3 d-none d-sm-block">
                    <button title="Reset Search" class="btn btn-primary" data-toggle="tooltip" id="reset-search-form"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
    </div>
{!! Form::close() !!} 
