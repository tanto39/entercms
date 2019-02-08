
<label for="name">Имя</label>
<input type="text" id="name" class="form-control" name="name" value="{{$user->name ?? ""}}" required>

<label for="email">Электронная почта</label>
<input type="email" id="email" class="form-control" name="email" value="{{$user->email ?? ""}}">

<label for="password">Пароль</label>
<input type="password" id="password" class="form-control" name="password" value="">

<label for="is_admin">Админ</label>
<select id="is_admin" class="form-control" name="is_admin">
    <option value="1" @if(isset($user->id) && $user->is_admin == 1) selected="" @endif>Да</option>
    <option value="0" @if(isset($user->id) && $user->is_admin == 0) selected="" @endif>Нет</option>
</select>

<input type="hidden" name="id" value="{{$user->id ?? ""}}">

<div class="form-buttons">
<input class="btn btn-primary" type="submit" name="save" value="Сохранить">

@if(isset($user->id))
    <input class="btn btn-danger" type="submit" name="delete" value="Удалить">
@endif
</div>