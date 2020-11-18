@extends('layouts.app')

@push('scripts')
    <script src="{{ asset('js/blog/like.js') }}"></script>
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <h5>Управление постами</h5>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="float-right">
                        {{ Breadcrumbs::render('adm-posts-list') }}
                    </div>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-9">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            @foreach($posts as $post)
                                {{ view('blog.post.adm.post_list_item', compact('post')) }}
                            @endforeach

                            <hr>

                            {{ $posts->onEachSide(1)->links('vendor.pagination.general') }}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-header">
                            Теги
                        </div>
                        <div class="card-body">
                            @foreach($tags as $tag)
                                <span class="badge bg-info">
                                    <a href="{{ route('post.tag', $tag->id) }}">{{ $tag->title }}</a>
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection