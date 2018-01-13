@if(count($propGroups) > 0)
    <div class="properties-wrap">
    <h3>Значения свойств:</h3>
    @foreach($propGroups as $propGroupName=>$properties)
        <h4>{{$propGroupName}}</h4>
        @foreach($properties as $propId=>$property)
            <label for="prop-{{$propId}}">{{$property['title']}}</label>
            @if($property['type'] === PROP_TYPE_LIST && !empty($property['arList']))
                <select id="prop-{{$propId}}" multiple class="form-control selectpicker" data-live-search="true" name="properties[{{$propId}}][]">
                    @foreach($property['arList'] as $listVal)
                        <option value="{{$listVal['id']}}"
                            @isset($property['value'])
                                @foreach($property['value'] as $selectedList)
                                    @if($selectedList == $listVal['id'])
                                        selected=""
                                    @endif
                                @endforeach
                            @endisset>{{$listVal['title']}}
                        </option>
                    @endforeach
                </select>
            @elseif($property['type'] === PROP_TYPE_CATEGORY_LINK && !empty($categories))
                <select id="prop_category_link" multiple class="form-control selectpicker" data-live-search="true" name="properties[{{$propId}}][]">
                    <option value="0">Без привязки</option>
                    @include('admin.properties.partials.categories', ['categories' => $categories])
                </select>
            @elseif($property['type'] === PROP_TYPE_ITEM_LINK && !empty($items))
                <select id="prop_item_link" multiple class="form-control selectpicker" data-live-search="true" name="properties[{{$propId}}][]">
                    <option value="0">Без привязки</option>
                    @foreach($items as $itemElement)
                        <option value="{{$itemElement['id']}}"
                            @if(isset($property['value']))
                                @foreach($property['value'] as $selectedItem)
                                    @if($selectedItem == $itemElement['id'])
                                        selected=""
                                    @endif
                                @endforeach
                            @endif>{{$itemElement['title']}}</option>
                    @endforeach
                </select>
            @elseif($property['type'] === PROP_TYPE_IMG)
                <div class="image-wrap">
                    <input type="file" multiple id="prop-{{$propId}}" class="form-control" name="properties[{{$propId}}][]">
                    <div class="image-property-placeholder flex">
                        @if(!empty($property['value']))
                            @foreach($property['value'] as $image)
                                <div class="img-item">
                                    <img src="{{ url('/' . PREV_IMG_FULL_PATH . $image['MIDDLE']) }}" alt="">
                                    <button class="btn btn-danger btn-delete" name="deletePropImg" value="{{$image['MIDDLE']}}">&#10008;</button>
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
                <input type="text" id="prop-{{$propId}}" class="form-control" name="properties[{{$propId}}]" value="@if(isset($property['value'])){{$property['value']}}@elseif(isset($property['default'])){{$property['default']}}@endif">
            @endif
        @endforeach
    @endforeach
    </div>
@endif