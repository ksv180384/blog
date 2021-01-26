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
                <div class="col-sm-12">
                    <h5>{{ $post->title }}</h5>
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
                                    <div class="div-img-circle" style="background-image: url({{ Storage::url($post->user->avatar) }})"></div>
                                    <span class="username">
                                        <a href="{{ route('profile.show', $post->user->id) }}">{{ $post->user->name }}</a>
                                    </span>
                                    <span class="description">
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

                                @if($post->getAttributes()['img'])
                                    <div class="post-excerpt-img" style="background-image: url({{  Storage::url($post->img) }});">

                                    </div>
                                @endif

                                <p>
                                    {!! $post->showHtmlContent !!}
                                </p>

                                <hr>

                                <div class="d-inline-block">
                                    @if(Auth::check())
                                        <div class="btn-like{{ $post->checkUserLike ? ' like-active' : '' }}">
                                            <a href="{{ route('post.toggle_like', $post->id) }}"
                                               class="btn-like-add link-black text-sm js-like"
                                            >
                                                <span class=""><i class="far fa-heart"></i></span> Нравится (<span class="js-like-count-el">{{ $post->likes_count }}</span>)
                                            </a>
                                            <a href="{{ route('post.toggle_like', $post->id) }}"
                                               class="btn-like-remove link-black text-sm js-like"
                                            >
                                                <span class="text-success"><i class="fas fa-heart"></i></span> Нравится (<span class="js-like-count-el">{{ $post->likes_count }}</span>)
                                            </a>
                                        </div>
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
                                        <a href="{{ route('post.tag', $tag->id) }}">{{ $tag->title }}</a>
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
                                @foreach($post->comments as $comment)
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