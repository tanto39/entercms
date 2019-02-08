@foreach($categories as $category_list)
    <option value="{{$category_list->id ?? ""}}"
        @isset($property->id)
            @if($property->category_id == $category_list->id)
                selected=""
            @endif
        @endisset

        @if(isset($property['type']) && isset($property['value']) && ($property['type'] == PROP_TYPE_CATEGORY_LINK))
            @foreach($property['value'] as $selectedICategory)
                @if($selectedICategory == $category_list->id)
                    selected=""
                @endif
            @endforeach
        @endif
    >
    {!! $delimiter ?? "" !!}{{$category_list->title ?? ""}}
    </option>

    @if(count($category_list->children) > 0)
        @include('admin.properties.partials.categories', [
            'categories' => $category_list->children,
            'delimiter' => ' - ' . $delimiter
        ])
    @endif
@endforeach