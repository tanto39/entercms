<form method="post" action="{{route('admin.propgroup.filter')}}">
    {{ csrf_field() }}

    <label for="sort">Сортировка</label>
    <select id="sort" class="form-control" name="sort">
        <option value="default">По умолчанию</option>
        <option value="dateUp" @if($sort == 'dateUp') selected="" @endif>По дате изменения (сначала новые)</option>
        <option value="dateDown" @if($sort == 'dateDown') selected="" @endif>По дате изменения (сначала старые)</option>
        <option value="title" @if($sort == 'title') selected="" @endif>По алфавиту</option>
    </select>

    <br>
    <input class="btn btn-primary" type="submit" name="exec" value="Выполнить">
    <input class="btn btn-primary" type="submit" name="reset" value="Сбросить">

</form>