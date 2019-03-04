<div>
    <ul class="list-group">
        @if($list->isEmpty())
            <li class="list-group-item">No Results Found</li>
        @else
            @foreach($list as $item)
                <li class="list-group-item">
                    <a href="#" class="customer-selection" data-val="{{$item->cust_id}}" data-name="{{$item->name}}">{{$item->cust_id}} - {{$item->name}}</a>
                </li>
            @endforeach
        @endif
    </ul>
</div>
