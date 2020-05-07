@if(!empty($itemLinks['pages'][2]))
    <div class="pagination-wrap">
        <nav>
            <ul class="pagination">

                @if($itemLinks['is_first_page'] == 'Y')
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link" aria-hidden="true">‹</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{$itemLinks['pages'][1]['url']}}" rel="prev" aria-label="« Previous">‹</a>
                    </li>
                @endif

                @foreach($itemLinks['pages'] as $key=>$itemLink)
                    @if($itemLink['active'] == 'Y')
                        <li class="page-item active" aria-current="page"><span class="page-link">{{$key}}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{$itemLink['url']}}">{{$key}}</a></li>
                    @endif
                @endforeach

                @if($itemLinks['is_last_page'] == 'Y')
                    <li class="page-item disabled" aria-disabled="true" aria-label="Next »">
                        <span class="page-link" aria-hidden="true">›</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{$itemLinks['pages'][$itemLinks['last_page']]['url']}}" rel="next" aria-label="Next »">›</a>
                    </li>
                @endif



            </ul>
        </nav>

    </div>
@endif
