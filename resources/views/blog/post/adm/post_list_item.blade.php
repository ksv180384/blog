<div class="post">
    <div class="user-block">
        <div class="div-img-circle" style="background-image: url({{ Storage::url($post->user->avatar) }})"></div>
        <span class="username">
            <a href="{{ route('adm_post.show', $post->id) }}">{{ $post->title }}</a>
            @if(Auth::check() && Auth::user()->can('blog-posts-edit'))
                <a href="{{ route('adm_post.edit', $post->id) }}" class="float-right btn-tool" title="Редактировать">
                    <i class="fas fa-pencil-alt mr-1"></i>
                </a>
            @endif
        </span>
        <span class="description">
            <a href="{{ route('profile.show', $post->user->id) }}" class="link-black" title="Автор">{{ $post->user->name }}</a> |

            <span title="Время публикации">
                @if($post->published_at)
                    <strong>
                        {{ $post->published_at->format('H:i') }}
                    </strong>
                    {{ $post->published_at->format('d.m.Y') }}
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
            <div class="post-excerpt-img" style="background-image: url({{  Storage::url($post->img) }});">

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
                    <a href="{{ route('post.like-remove', $post->id) }}" class="link-black text-sm text-success js-like">
                        <i class="far fa-thumbs-up mr-1"></i> Нравится (<span class="js-like-count-el">{{ $post->likesСount }}</span>)
                    </a>
                @else
                    <a href="{{ route('post.like-add', $post->id) }}" class="link-black text-sm js-like">
                        <i class="far fa-thumbs-up mr-1"></i> Нравится (<span class="js-like-count-el">{{ $post->likesСount }}</span>)
                    </a>
                @endif
            @else
                <span class="text-sm">
                    <i class="far fa-thumbs-up mr-1"></i> Нравится (<span class="js-like-count-el">{{ $post->likesСount }}</span>)
                </span>
            @endif

            <span class="text-sm ml-2">
                <i class="far fa-comments mr-1"></i> Комментариев ({{ $post->commentsCount }})
            </span>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="float-right-md">
                @foreach($post->tags as $tag)
                    <span class="badge bg-info">
                        <a href="{{ route('post.tag', $tag->id) }}">{{ $tag->title }}</a>
                    </span>
                @endforeach
            </div>
        </div>
    </div>
</div>