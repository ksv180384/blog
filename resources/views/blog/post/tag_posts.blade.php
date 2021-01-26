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
                    <h5>Посты тега {{ $tag->title }}</h5>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="float-right">
                        {{ Breadcrumbs::render('posts-tag-list', $tag) }}
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

                            @foreach($posts as $post)
                                {{ view('blog.post.post_list_item', compact('post')) }}
                            @endforeach

                            <hr>

                            {{ $posts->onEachSide(1)->links('vendor.pagination.general') }}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection