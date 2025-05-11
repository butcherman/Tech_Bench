<div
    style="border-bottom: 1px solid #585555"
    class="mb-3 pb-3"
>
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-2">
                Customer Files Report
            </h1>
        </div>
    </div>
</div>

@foreach ($data as $group => $custList)
    <div class="my-3">
        <x-base.card :title="'Showing Customers that ' . $params['hasInput'] . ' a ' . $group . ' file'">
            <div class="mb-8">{{ $custList->count() }} Customers Total</div>
            @foreach ($custList as $cust)
                <div>{{ $cust->name }}</div>
            @endForEach
        </x-base.card>
    </div>
@endForEach
