@if(count($propGroups) > 0)
    <h3>Значения свойств:</h3>
    @foreach($propGroups as $propGroupName=>$properties)
        <h4>{{$propGroupName}}</h4>
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
            @elseif($property['type'] === PROP_TYPE_IMG)
                <div class="image-wrap">
                    <input type="file" multiple id="prop-{{$propId}}" class="form-control" name="properties[{{$propId}}][]">
                    <div class="image-property-placeholder" style="display: flex">
                        @if(!empty($property['value']))
                            @foreach($property['value'] as $image)
                                <div class="img-item">
                                    <img style="max-width: 200px;" src="{{ url('/' . PREV_IMG_FULL_PATH . $image['MIDDLE']) }}" alt="">
                                    <button class="btn btn-danger" name="deletePropImg" value="{{$image['MIDDLE']}}">Удалить</button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @elseif($property['type'] === PROP_TYPE_FILE)
                <div class="file-wrap">
                    <input type="file" id="prop-{{$propId}}" class="form-control" name="properties[{{$propId}}]">
                    @if(!empty($property['value']))
                        <div class="file-item">
                            <a download="" href="{{ url('/' . FILE_LOAD_PATH . $property['value']) }}">{{$property['value']}}</a>
                            <button class="btn btn-danger" name="deletePropFile" value="{{$property['value']}}">Удалить</button>
                        </div>
                    @endif
                </div>
            @else
                <input type="text" id="prop-{{$propId}}" class="form-control" name="properties[{{$propId}}]" value="@if(isset($property['value'])){{$property['value']}}@elseif(isset($property['default'])){{$property['default']}}@else @endif">
            @endif
        @endforeach
    @endforeach
@endif