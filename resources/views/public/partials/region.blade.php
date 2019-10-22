<div class="region container">
    <form class="region-form flex" method="POST"  action="{{route('item.setregion')}}">
        {{csrf_field()}}
        <label for="region_select">Выберите город:</label>
        <select id="region_select" class="form-control region_select" name="regionId">
        <option value="0">Не выбрано</option>
        @foreach($template->regions as $region)
                <option value="{{$region["id"]}}" @if(!empty($template->selectedRegion[0]['id']) && $region['id']==$template->selectedRegion[0]['id']) selected="" @endif>{{$region["title"]}}</option>
            @endforeach
        </select>

        <div class="form-buttons flex">
            <input class="btn btn-primary" type="submit" name="setRegionPublic" value="Установить">
        </div>
    </form>
</div>