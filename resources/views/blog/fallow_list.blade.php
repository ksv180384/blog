@extends('layouts.app')

@push('scripts')
    <script src="{{ asset('js/blog/post/follow_list.js') }}"></script>
    <script src="{{ asset('js/blog/like.js') }}"></script>
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Подписан</h5>
                </div>
                <div class="col-sm-6">
                    <div class="float-right">
                        {{ Breadcrumbs::render('follow-list') }}
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-9">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">

                            @if($posts)
                                @foreach($posts as $post)
                                    {{ view('blog.post.post_list_item', compact('post')) }}
                                @endforeach

                                <hr>

                                {{ $posts->onEachSide(1)->links('vendor.pagination.general') }}
                            @else
                                <div class="text-center">
                                    Нет постов
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-xs-12 col-sm-12 col-md-3">
                    <div class="card">
                        <div class="card-body p-0">
                            @forelse($user->followed as $follow)
                                <div class="block p-2 js-user-item">
                                    <div class="div-img-circle mr-2"
                                         style="background-image: url({{ Storage::url($follow->avatar) }})"></div>
                                    <span class="username">
                                        <a href="{{ route('profile.show', $follow->id) }}">
                                            {{ $follow->name }}
                                        </a>
                                        <span class="float-right">
                                            <form action="{{ route('follow.destroy', $follow->id) }}"
                                                  method="post" class="js-form-follow-remove">
                                                @csrf
                                                <button type="submit" class="btn btn-tool" title="Отписаться">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </span>
                                    </span>

                                </div>
                            @empty
                                <div class="text-center">
                                    Нет отслеживаемых
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection