<div class="image-wrap">
    <label for="preview_img">Изображение</label>
    <input type="file" multiple id="preview_img" class="form-control" name="preview_img[]">

    <div id="image-placeholder" class="flex">
        @if(!empty($preview_images))
            @foreach($preview_images as $image)
                <div class="img-item">
                    <img src="{{ url('/images/shares/previews/'.$image['MIDDLE']) }}" alt="">
                    <button class="btn btn-danger btn-delete" name="deleteImg" value="{{$image['MIDDLE']}}">&#10008;</button>
                </div>
            @endforeach
        @endif
    </div>
</div>