@if(count($properties) > 0)
    <h3>Значения свойств:</h3>
    @foreach($properties as $propId=>$property)
        <label for="prop-{{$propId}}">{{$property['title']}}</label>
        @if($property['type'] === PROP_TYPE_LIST && !empty($property['arList']))
            <select id="prop-{{$propId}}" class="form-control selectpicker" data-live-search="true" name="properties[{{$propId}}]">
                @foreach($property['arList'] as $listVal)
                    <option value="{{$listVal['id']}}" @if(isset($property['value']) && $property['value'] == $listVal['id']) selected="" @endif>{{$listVal['title']}}</option>
                @endforeach
            </select>
        @elseif($property['type'] === PROP_TYPE_CATEGORY_LINK && !empty($categories))
            <select id="prop_category_link" class="form-control selectpicker" data-live-search="true" name="properties[{{$propId}}]">
                <option value="0">Без привязки</option>
                @include('admin.properties.partials.categories', ['categories' => $categories])
            </select>
        @else
            <input type="text" id="prop-{{$propId}}" class="form-control" name="properties[{{$propId}}]" value="@if(isset($property['value'])){{$property['value']}}@elseif(isset($property['default'])){{$property['default']}}@else @endif">
        @endif
    @endforeach
@endif