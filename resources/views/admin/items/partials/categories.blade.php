@foreach($categories as $category_list)
    <option value="{{$category_list->id or ""}}"
        @isset($item->id)
            @if($item->category_id == $category_list->id)
                selected=""
            @endif

            @if($item->id == $category_list->id)
                hidden=""
            @endif
        @endisset
    >
    {!! $delimiter or "" !!}{{$category_list->title or ""}}
    </option>

    @if(count($category_list->children) > 0)
        @include('admin.items.partials.categories', [
            'categories' => $category_list->children,
            'delimiter' => ' - ' . $delimiter
        ])
    @endif
@endforeach