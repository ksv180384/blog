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
                                    <div class="div-img-circle" style="background-image: url({{ $post->avatar }})"></div>
                                    <span class="username">
                                        <a href="{{ route('profile.show', $post->user_id) }}">{{ $post->name }}</a>
                                    </span>
                                    <span class="description">
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
                                </div>
                                <!-- /.user-block -->
                                <p>
                                    {{ $post->content }}
                                </p>

                                <hr>

                                <div class="d-inline-block">
                                    @if(Auth::check())
                                        @if($like)
                                            <a href="{{ route('post.like-remove', $like->id) }}"
                                               class="link-black text-sm text-success js-like">
                                                <i class="far fa-thumbs-up mr-1"></i>
                                                Нравится (<span class="js-like-count-el">{{ $count_like }}</span>)
                                            </a>
                                        @else
                                            <a href="{{ route('post.like-add', $post->id) }}"
                                               class="link-black text-sm js-like">
                                                <i class="far fa-thumbs-up mr-1"></i>
                                                Нравится (<span class="js-like-count-el">{{ $count_like }}</span>)
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-sm">
                                            <i class="far fa-thumbs-up mr-1"></i>
                                            Нравится (<span class="js-like-count-el">{{ $count_like }}</span>)
                                        </span>
                                    @endif
                                </div>

                                <div class="float-right ml-2">
                                    @foreach($tags as $tag)
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
                            Комментарии (<span id="countComments">{{ $comments_count }}</span>)
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