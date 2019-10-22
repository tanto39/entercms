<div class="reviews">
    @if($result['slug'] != REVIEWS_SLUG)<h3>Отзывы о {{$result['title']}}</h3>@endif
    @if(!empty($result['reviews']))
        @foreach($result['reviews'] as $review)
            <div class="review-item">
                <div class="review-autor">{{$review['author_name']}}</div>
                <div class="review-content"><p>{{$review['full_content']}}</p></div>
                <div class="review-rating"><p>Оценка: {{$review['rating']}}</p></div>
                <div class="review-date"><span>{{$review['created_at']}}</span></div>
            </div>
        @endforeach
    @endif

    <form class="form-horizontal review-form" method="post" action="{{route('item.review.store')}}">
        <h4>Оставить отзыв</h4>
        {{csrf_field()}}
        <label for="author_name">Имя</label>
        <input type="text" id="author_name" class="form-control" name="author_name" required>

        <div class="rating-wrap">
            <label>Оценка:</label>
            <div class="rating">
                @for($i = 1; $i <= 5; $i++)
                    <input type="radio" name="rating" value="{{$i}}">
                @endfor
            </div>
        </div>

        <label for="full_content">Отзыв</label>
        <textarea id="full_content" class="form-control" name="full_content" rows="5"></textarea>

        <label>Защита от спама: введите сумму 2+2:</label><input class="form-control form-capcha" type="number" required name="capcha"/>
        <div class="form-input form-pd"><label>Даю согласие на обработку <a href="#" target="_blank" rel="noopener noreferrer">персональных данных</a>:</label><input class="checkbox-inline" type="checkbox" required="" name="pd" /></div>


        <input type="hidden" name="item_id" value="{{$result['id'] ?? ''}}">

        <div class="form-buttons">
            <input class="btn btn-primary" type="submit" name="save" value="Отправить">
        </div>
    </form>
</div>

