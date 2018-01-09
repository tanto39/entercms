@if(!empty($result['preview_img']))
    <div class="detail-image-big-wrap">
        <figure class="detail-image-big flex">
            <img @if(isset($result['is_product']) && $result['is_product'] == 1)itemprop="image"@endif class="detail-image-big-img" alt="{{$result['title']}}" src="{{$result['preview_img'][0]['MIDDLE']}}">
        </figure>
    </div>

    <div class="detail-image-small-block flex">
        @foreach($result['preview_img'] as $key=>$photo)
            <a class="detail-image-small-item flex @if($key == 0) active @endif" href="{{$photo['MIDDLE']}}" data-original-src="{{$photo['MIDDLE']}}" data-full-src="{{$photo['FULL']}}" onclick="enterShop.changePicture($(this).data('original-src'), $('.detail-image-big-img'), $(this), event)">
                <img class="detail-image-small-item-img" src="{{$photo['SMALL']}}" alt=""/>
            </a>
        @endforeach
    </div>
@endif