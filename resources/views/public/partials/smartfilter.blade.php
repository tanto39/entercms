<form class="smart-filter" method="get">
    <h3>Фильтр по товарам</h3>
    @foreach($properties as $key=>$property)
        <div class="smart-filter-item">
            <span class="smart-filter-prop-title">{{$property['title']}}</span>
            @if($property['type'] == PROP_TYPE_NUM)
                <div class="flex smart-filter-wrap_num">
                    <div class="smart-filter-item_num">
                        <span>От:</span>
                        <input type="number" class="form-control" name="property[{{$property['id']}}][from]" value="{{$property['values']['from'] or 0}}">
                    </div>
                    <div class="smart-filter-item_num">
                        <span>До:</span>
                        <input type="number" class="form-control" name="property[{{$property['id']}}][to]" value="{{$property['values']['to'] or ""}}">
                    </div>
                </div>
            @elseif($property['type'] == PROP_TYPE_LIST)
                @foreach($property['arValues'] as $valKey=>$listValue)
                    <div class="smart-filter-item-list flex">
                        <label for="property_{{$property['id']}}">{{$listValue['title']}}</label>
                        <input type="checkbox" id="property_{{$property['id']}}" name="property[{{$property['id']}}][arListValues][{{$valKey}}]" value="{{$listValue['id']}}" @if($listValue['selected'] == 'Y') checked @endif/>
                    </div>
                @endforeach
            @endif
        </div>
    @endforeach

    <div class="form-buttons flex">
        <input class="btn btn-primary" type="submit" name="setfilter" value="Установить">
        <input class="btn btn-danger" type="submit" name="unsetfilter" value="Сбросить">
    </div>
</form>