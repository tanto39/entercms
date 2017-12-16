<form method="post" action="{{route('admin.order.filter')}}">
    {{csrf_field()}}

    <label for="status_order-select">Статус заказа</label>
    <select id="status_order-select" class="form-control" name="filter[status_order]">
        <option value="all">Все статусы</option>
        @foreach($status_orders as $status_order)
            <option value="{{$status_order->id}}" @if(isset($filter['status_order']) && $filter['status_order'] == $status_order->id) selected="" @endif>{{$status_order->title}}</option>
        @endforeach
    </select>

    <br>

    <label for="sort">Сортировка</label>
    <select id="sort" class="form-control" name="sort">
        <option value="default">По умолчанию</option>
        <option value="dateUp" @if($sort == 'dateUp') selected="" @endif>По дате изменения (сначала новые)</option>
        <option value="dateDown" @if($sort == 'dateDown') selected="" @endif>По дате изменения (сначала старые)</option>
        <option value="title" @if($sort == 'title') selected="" @endif>По алфавиту</option>
    </select>

    <div class="form-buttons">
        <input class="btn btn-primary" type="submit" name="exec" value="Выполнить">
        <input class="btn btn-primary" type="submit" name="reset" value="Сбросить">
    </div>
</form>