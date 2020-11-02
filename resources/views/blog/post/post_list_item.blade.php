<div class="post">
    <div class="user-block">
        <div class="div-img-circle" style="background-image: url({{ $post->avatar }})"></div>
        <span class="username">
            <a href="{{ route('post.show', $post->id) }}">{{ $post->title }}</a>
            @if(Auth::check() && $post->user_id == Auth::user()->id))
                <a href="{{ route('post.edit', $post->id) }}" class="float-right btn-tool" title="Редактировать">
                    <i class="fas fa-pencil-alt mr-1"></i>
                </a>
            @endif
        </span>
        <span class="description">
            <a href="{{ route('profile.show', $post->user_id) }}" class="link-black" title="Автор">{{ $post->name }}</a> |

            <span title="Время публикации">
                @if($post->published_at)
                    <strong>
                        {{ \Carbon\Carbon::createFromFormat(
                                    'Y-m-d H:i:s',
                                    $post->published_at
                            )->format('H:i') }}
                    </strong>
                    {{ \Carbon\Carbon::createFromFormat(
                                    'Y-m-d H:i:s',
                                    $post->published_at
                            )->format('d.m.Y') }}
                @else
                    <span class="text-danger">неопубликованно</span>
                @endif
            </span>
        </span>
    </div>
    <!-- /.user-block -->
    <div style="clear: both"></div>
    @if($post->getAttributes()['img'])
        <div>
            <div class="post-excerpt-img" style="background-image: url({{  $post->img }});">

            </div>
            <div class="post-excerpt">
                {{ $post->excerpt }}
            </div>
        </div>
    @else
        <div>
            {{ $post->excerpt }}
        </div>
    @endif

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6">
            @if(Auth::check())
                @if($post->check_like)
                    <a href="{{ route('post.like-remove', $post->check_like) }}" class="link-black text-sm text-success js-like">
                        <i class="far fa-thumbs-up mr-1"></i> Нравится (<span class="js-like-count-el">{{ $post->likes_count }}</span>)
                    </a>
                @else
                    <a href="{{ route('post.like-add', $post->id) }}" class="link-black text-sm js-like">
                        <i class="far fa-thumbs-up mr-1"></i> Нравится (<span class="js-like-count-el">{{ $post->likes_count }}</span>)
                    </a>
                @endif
            @else
                <span class="text-sm">
                    <i class="far fa-thumbs-up mr-1"></i> Нравится (<span class="js-like-count-el">{{ $post->likes_count }}</span>)
                </span>
            @endif

            <span class="text-sm ml-2">
                <i class="far fa-comments mr-1"></i> Комментариев ({{ $post->comments_count }})
            </span>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="float-right-md">
                @foreach($tags_to_post as $item)
                    @if($item->post_id == $post->id)
                        <span class="badge bg-info">
                            <a href="{{ route('post.tag', $item->id) }}">{{ $item->title }}</a>
                        </span>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>