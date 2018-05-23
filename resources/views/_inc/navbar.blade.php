@foreach($navCategories as $cat)
    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="{{ $cat }}">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapse{{ $cat }}" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-folder"></i>
            <span class="nav-link-text">{{ $cat }} </span>
        </a>
        <ul class="sidenav-second-level collapse" id="collapse{{ $cat }}">
            @foreach($navSystems as $parent => $sys)
                @if($sys['category'] === $cat)
                    @if(isset($sys['parent']))
                        <li>
                            <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#collapse{{ $sys['name'] }}"><i class="fa fa-fw fa-folder"></i> {{ $sys['name'] }}</a>
                            <ul class="sidenav-third-level collapse" id="collapse{{ $sys['name'] }}">
                                @foreach($navSubSystems as $sub)
                                    @if($sub['parent'] == $parent)
                                        <li>
                                            <a href="{{ $sub['url'] }}"><i class="fa fa-fw fa-bars"></i> {{ $sub['name'] }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li>
                            
                            <a href="{{ $sys['url'] }}"><i class="fa fa-fw fa-bars"></i> {{ $sys['name'] }}</a>
                        </li>
                    @endif
                @endif
            @endforeach
        </ul>
    </li>
@endforeach
