<form id="smart-filter" class="smart-filter" method="get">
    <h3>Фильтр по товарам</h3>
    @foreach($properties as $key=>$property)
        <div class="smart-filter-item">
            <span class="smart-filter-prop-title">{{$property['title']}}</span>
            @if($property['type'] == PROP_TYPE_NUM)
                <div class="flex smart-filter-wrap_num">
                    <div class="smart-filter-item_num">
                        <span>От:</span>
                        <input type="number" class="form-control" name="property[{{$property['id']}}][from]" value="@if($property['values']['from']){{$property['values']['from']}}@endif">
                    </div>
                    <div class="smart-filter-item_num">
                        <span>До:</span>
                        <input type="number" class="form-control" name="property[{{$property['id']}}][to]" value="@if($property['values']['to']){{$property['values']['to']}}@endif">
                    </div>
                </div>
            @elseif($property['type'] == PROP_TYPE_LIST)
                @foreach($property['arValues'] as $valKey=>$listValue)
                    <div class="smart-filter-item-list flex">
                        <label for="property_list_{{$listValue['id']}}">{{$listValue['title']}}</label>
                        <input type="checkbox" id="property_list_{{$listValue['id']}}" name="property[{{$property['id']}}][arListValues][]" value="{{$listValue['id']}}" @if($listValue['selected'] == 'Y') checked @endif/>
                    </div>
                @endforeach
            @else
                <div class="smart-filter-item-text">
                    <input class="form-control" type="text" id="property_{{$property['id']}}" name="property[{{$property['id']}}][text]" value="{{$property['text']}}"/>
                </div>
            @endif
        </div>
    @endforeach

    <div class="form-buttons flex">
        <input class="btn btn-primary" type="submit" name="setfilter" value="Установить">
        <input class="btn btn-danger" type="submit" name="unsetfilter" value="Сбросить">
    </div>
</form>