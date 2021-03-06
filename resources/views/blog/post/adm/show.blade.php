@extends('layouts.app')

@push('scripts')
    <script src="{{ asset('js/blog/post/post.js') }}"></script>
    <script src="{{ asset('js/blog/like.js') }}"></script>
    <script src="{{ asset('js/blog/post/comment.js') }}"></script>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <h5>{{ $post->title }}</h5>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="float-right">
                        {{ Breadcrumbs::render('adm-post-show', $post) }}
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <div class="post clearfix">
                                <div class="user-block">
                                    <div class="float-right">
                                        @if($post->published_at)
                                            <button
                                                    id="offPublished"
                                                    class="btn btn-sm btn-default"
                                                    data-url="{{ route('post.published', $post->id) }}"
                                            >Снять с публикации</button>
                                        @else
                                            <button
                                                    id="published"
                                                    class="btn btn-sm btn-primary"
                                                    data-url="{{ route('post.published', $post->id) }}"
                                            >Опубликовать</button>
                                        @endif
                                    </div>
                                    <div class="div-img-circle" style="background-image: url({{ Storage::url($post->user->avatar) }})"></div>
                                    <span class="username">
                                        <a href="{{ route('profile.show', $post->user->id) }}">{{ $post->user->name }}</a>
                                    </span>
                                    <span class="description" id="datePublished">
                                        @if($post->published_at)
                                            <strong>
                                                {{ $post->published_at->format('H:i') }}
                                            </strong>
                                            {{ $post->published_at->format('d.m.Y') }}
                                        @else
                                            <span class="text-danger">неопубликованно</span>
                                        @endif
                                    </span>
                                </div>
                                <!-- /.user-block -->
                                <p>
                                    {!! $post->showHtmlContent !!}
                                </p>

                                <hr>

                                <div class="d-inline-block">
                                    @if(Auth::check())
                                        @if($like)
                                            <a href="{{ route('post.toggle_like', $post->id) }}"
                                               class="link-black text-sm text-success js-like">
                                                <i class="far fa-thumbs-up mr-1"></i>
                                                Нравится (<span class="js-like-count-el">{{ $post->likes_count }}</span>)
                                            </a>
                                        @else
                                            <a href="{{ route('post.toggle_like', $post->id) }}"
                                               class="link-black text-sm js-like">
                                                <i class="far fa-thumbs-up mr-1"></i>
                                                Нравится (<span class="js-like-count-el">{{ $post->likes_count }}</span>)
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-sm">
                                            <i class="far fa-thumbs-up mr-1"></i>
                                            Нравится (<span class="js-like-count-el">{{ $post->likes_count }}</span>)
                                        </span>
                                    @endif
                                </div>

                                <div class="float-right ml-2">
                                    @foreach($post->tags as $tag)
                                        <span class="badge bg-info">
                                        <a href="#{{ $tag->id }}">{{ $tag->title }}</a>
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card">
                        <div class="content-header">
                            Комментарии (<span id="countComments">{{ $post->comments_count }}</span>)
                        </div>
                        <div class="card-body">
                            <div id="commentsList" class="">
                                @foreach($comments as $comment)
                                    @include('blog.post.comment_item', ['comment' => $comment])
                                @endforeach
                            </div>

                            @if(Auth::check())
                                <div class="mt-4">
                                    <form id="sendComment" class="form-horizontal" method="post" action="{{ route('comment.add') }}">
                                        @csrf
                                        <div class="input-group input-group-sm mb-0">
                                            <input name="comment" class="form-control form-control-sm js-comment" placeholder="Написать комментарий">
                                            <input name="post_id" type="hidden" value="{{ $post->id }}">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary" id="sendCommentBtn">Отправить</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection