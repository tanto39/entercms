<div class="sortform">
    {{csrf_field()}}
    <label for="sort">Сортировка</label>
    <select form="smart-filter" id="sort" class="form-control sort-select" name="sortCategoryPublic">
        <option value="sortprop_default">По умолчанию</option>

        @foreach($sortProps as $sortProp)
            <option value="sortprop_up_{{$sortProp["id"]}}" @if($arSortProp[1]=='up' && $arSortProp[2]==$sortProp["id"]) selected="" @endif>{{$sortProp["title"]}} вверх</option>
            <option value="sortprop_down_{{$sortProp["id"]}}" @if($arSortProp[1]=='down' && $arSortProp[2]==$sortProp["id"]) selected="" @endif>{{$sortProp["title"]}} вниз</option>
        @endforeach
    </select>

    <div class="form-buttons flex">
        <input form="smart-filter" class="btn btn-primary" type="submit" name="setsortCategoryPublic" value="Установить">
        <input form="smart-filter" class="btn btn-danger" type="submit" name="unsetsortCategoryPublic" value="Сбросить">
    </div>
</div>