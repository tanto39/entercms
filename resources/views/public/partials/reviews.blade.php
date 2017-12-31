<div class="reviews">
    <h4>Отзывы</h4>
    @if(!empty($result->reviews))
        @foreach($result->reviews as $review)
            <div class="review-item">
                <div class="review-autor">{{$review->author_name}}</div>
                <div class="review-content"><p>{{$review->full_content}}</p></div>
                <div class="review-rating"><p>Оценка: {{$review->rating}}</p></div>
                <div class="review-date"><dd>{{$review->created_at}}</dd></div>
            </div>
        @endforeach
    @endif

    <form class="form-horizontal review-form" method="post" action="{{route('item.review.store')}}">
        <h4>Оставить отзыв</h4>
        {{csrf_field()}}
        <label for="author_name">Имя</label>
        <input type="text" id="author_name" class="form-control" name="author_name" required>

        <div class="rating-wrap">
            <label for="rating">Оценка:</label>
            <div class="rating">
                @for($i = 1; $i <= 5; $i++)
                    <input type="radio" name="rating" value="{{$i}}">
                @endfor
            </div>
        </div>

        <label for="full_content">Отзыв</label>
        <textarea id="full_content" class="form-control" name="full_content" rows="5"></textarea>

        <input type="hidden" name="item_id" value="{{$result->id or ''}}" required>

        <div class="form-buttons">
            <input class="btn btn-primary" type="submit" name="save" value="Отправить">
        </div>
    </form>
</div>

