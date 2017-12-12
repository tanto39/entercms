@extends('admin.layouts.app_admin')

@section('content')

    @component('admin.components.breadcrumbs')
        @slot('title') Список отзывов @endslot
        @slot('active') Отзывы @endslot
    @endcomponent

    <hr>
        @component('admin.components.search')
            @slot('searchRoute') admin.review.index @endslot
            @slot('searchText') {{$searchText}} @endslot
        @endcomponent
    <hr>
        <a class="btn btn-default" href="{{route('admin.review.create')}}">Создать отзыв</a>
    <hr>
        @include('admin.reviews.partials.filter')
    <hr>

    @foreach($reviews as $review)
        <form id="form-{{$review->id}}" method="post" action="{{route('admin.review.update', $review)}}">
            <input type="hidden" name="_method" value="put">
            {{csrf_field()}}
        </form>
    @endforeach

    <table class="table table-striped">
        <thead>
            <th>Название</th>
            <th>Порядок</th>
            <th>Изменено</th>
            <th>Символьный код</th>
            <th>Редактировать</th>
            <th>Применить</th>
            <th>Удалить</th>
        </thead>

        <tbody>
            @forelse($reviews as $review)
                <tr>
                    <td><input type="text" class="form-control" name="title" value="{{$review->title or ""}}" form="form-{{$review->id}}" required></td>
                    <td><input type="number" class="form-control" style="width: 90px;" name="order" value="{{$review->order or ""}}" form="form-{{$review->id}}"></td>
                    <td>{{$review->updated_at}}</td>
                    <td><input type="text" class="form-control" name="slug" value="{{$review->slug or ""}}" form="form-{{$review->id}}"></td>
                    <td>
                        <a href="{{route('admin.propgroup.edit', $review)}}"><span style="font-size:2em">&#9998;</span></a>
                    </td>
                    <td><input class="btn btn-primary" type="submit" name="saveFromList" value="Ок" form="form-{{$review->id}}"></td>
                    <td><input class="btn btn-danger" style="font-size: 2em;padding: 2px 5px; line-height: 1;" type="submit" name="delete" value="&#10008;" form="form-{{$review->id}}"></td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        Отзывы отсутствуют
                    </td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="3">
                    <ul class="pagination">
                        {{$reviews->links()}}
                    </ul>
                </td>
            </tr>
        </tfoot>
    </table>
@endsection

<script type="text/javascript">

</script>