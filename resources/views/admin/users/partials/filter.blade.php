<form method="post" action="{{route('admin.user.filter')}}">
    {{ csrf_field() }}

    <label for="is_admin-select">Является админом</label>
    <select id="is_admin-select" class="form-control" name="filter[is_admin]">
        <option value="all">Все пользователи</option>
        <option value="1" @if(isset($filter['is_admin']) && $filter['is_admin'] == 1) selected="" @endif>Да</option>
        <option value="0" @if(isset($filter['is_admin']) && $filter['is_admin'] == 0) selected="" @endif>Нет</option>
    </select>

    <br>

    <label for="sort">Сортировка</label>
    <select id="sort" class="form-control" name="sort">
        <option value="dateUp" @if($sort == 'dateUp') selected="" @endif>По дате изменения (сначала новые)</option>
        <option value="dateDown" @if($sort == 'dateDown') selected="" @endif>По дате изменения (сначала старые)</option>
        <option value="title" @if($sort == 'title') selected="" @endif>По алфавиту</option>
    </select>

    <br>
    <input class="btn btn-primary" type="submit" name="exec" value="Выполнить">
    <input class="btn btn-primary" type="submit" name="reset" value="Сбросить">

</form>