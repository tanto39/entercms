@if(count($properties) > 0)
    <h3>Значения свойств:</h3>
    @foreach($properties as $propId=>$property)
        <label for="prop-{{$propId}}">{{$property['title']}}</label>
        <input type="text" id="prop-{{$propId}}" class="form-control" name="properties[{{$propId}}]" value="{{$property['value'] or ""}}">
    @endforeach
@endif