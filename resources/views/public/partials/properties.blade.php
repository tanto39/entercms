@if(!empty($result['properties']))
    @foreach($result['properties'] as $groupName=>$propGroup)
        <table class="table table-striped table-properties">
            <thead>
                <tr>
                    <th colspan="2">{{$groupName}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($propGroup as $propId=>$property)
                    <tr>
                        @if($property['type'] == PROP_TYPE_IMG)
                            <td colspan="2">
                                @if(!empty($property['value']))
                                <p>{{$property['title']}}</p>
                                    <div class="property-image flex">
                                        @foreach($property['value'] as $key=>$photo)
                                            <a class="property-image-small-item flex @if($key == 0) active @endif" href="{{$photo['MIDDLE']}}" data-original-src="{{$photo['MIDDLE']}}" data-full-src="{{$photo['FULL']}}">
                                                <img class="property-image-small-item-img" src="{{$photo['SMALL']}}" alt="{{$property['title']}}"/>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                        @elseif($property['type'] == PROP_TYPE_ITEM_LINK)
                            <td colspan="2">
                                <p>{{$property['title']}}</p>
                                @if(!empty($property['arItem']))
                                    <div class="property-item-link flex">
                                        @foreach($property['arItem'] as $key=>$linkItem)
                                            <a class="property-item-link-block" href="{{$linkItem['slug']}}">
                                                <div class="property-item-link-title">{{$linkItem['title']}}</div>
                                                @if(isset($linkItem['preview_img'][0]))
                                                    <img class="property-item-link-img" src="{{$linkItem['preview_img'][0]['MIDDLE']}}" alt="{{$property['title']}}"/>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                        @elseif($property['type'] == PROP_TYPE_CATEGORY_LINK)
                            <td colspan="2">
                                <p>{{$property['title']}}</p>
                                @if(!empty($property['arItem']))
                                    <div class="property-item-link flex">
                                        @foreach($property['arItem'] as $key=>$linkItem)
                                            <a class="property-item-link-block" href="{{$linkItem['slug']}}">
                                                <div class="property-item-link-title">{{$linkItem['title']}}</div>
                                                @if(isset($linkItem['preview_img'][0]))
                                                    <img class="property-item-link-img" src="{{$linkItem['preview_img'][0]['MIDDLE']}}" alt="{{$property['title']}}"/>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                        @else
                            <td class="table-property-proptitle">{{$property['title']}}</td>
                            <td>
                                @if($property['type'] == PROP_TYPE_FILE)
                                    <a download="" href="{{ url('/' . FILE_LOAD_PATH . $property['value']) }}">{{$property['value']}}</a>
                                @elseif($property['type'] == PROP_TYPE_LIST)
                                    @foreach($property['arList'] as $key=>$listItem)
                                        <div>{{$listItem['title']}}</div>
                                    @endforeach
                                @else
                                    {{$property['value']}}
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
@endif