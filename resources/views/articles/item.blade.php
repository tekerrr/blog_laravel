<div class="my-4">
    <div class="media">

        <div class="col-2 mr-3">
            <img src="/img/article/default_img.png"
                 alt="post img" class="img-fluid">
        </div>

        <div class="media-body">
            <h3>{{ $article['title'] }}</h3>
            <p>{{ $article['abstract'] }}</p>

            <div class="row mx-0 justify-content-end">
                <p class=""><i>{{ $article['created_at'] }}</i></p>
                <a class="btn btn-outline-primary rounded-0 ml-auto"
                   href="{{ $article['path'] }}">
                    ПОДРОБНЕЕ
                </a>
            </div>
        </div>
    </div>
</div>
