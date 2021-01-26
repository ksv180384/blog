@extends('layouts.app')

@push('scripts')
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.20.0/trumbowyg.min.js" integrity="sha256-oFd4Jr73mXNrGLxprpchHuhdcfcO+jCXc2kCzMTyh6A=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.20.0/langs/ru.min.js" integrity="sha256-ieaZIb9bJQP/HRf8j1NEPLfXXOWzLrue1h6tAuYfI3I=" crossorigin="anonymous"></script>

    <script src="{{ asset('js/blog/post/edit.js') }}"></script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('js/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.20.0/ui/trumbowyg.min.css" integrity="sha256-B6yHPOeGR8Rklb92mcZU698ZT4LZUw/hTpD/U87aBPc=" crossorigin="anonymous" />
    <!--link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css"-->
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper ml-0">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <h5>{{ $post->title }}</h5>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="float-right">
                            {{ Breadcrumbs::render('post-edit-my', $post) }}
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <!-- /.col -->
                    <div class="col-md-12">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <!-- /.card-header -->
                            <div class="card-body">

                                <form action="{{ route('post.update', $post->id) }}" method="post" enctype="multipart/form-data" id="formPostUpdate">
                                    @method('PATCH')
                                    @csrf

                                    <div class="list-group list-group-unbordered mb-3">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12 col-lg-3 min-h-240">
                                                <div class="add-post-img js-post-img" style="background-image: url({{ Storage::url($post->img) }})">
                                                    <input type="file" name="img" accept="image/*" class="img-input" id="imgPost">
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-12 col-lg-9">
                                                <div class="list-group-item border-0">
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword1">Заголовок</label>
                                                        <input type="text"
                                                               name="title"
                                                               class="form-control"
                                                               placeholder="Заголовок"
                                                               value="{{ old('title', $post->title) }}">
                                                    </div>
                                                </div>
                                                <div class="list-group-item border-0">
                                                    <strong>Коротко</strong>
                                                    <a class="d-block">
                                                        <textarea name="excerpt"
                                                                  class="form-control"
                                                                  cols="30"
                                                                  rows="4">{{ old('excerpt', $post->excerpt) }}</textarea>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <strong>Пост</strong>
                                            <a class="d-block">
                                                <textarea name="content"
                                                          class="form-control wysiwyg"
                                                          cols="30"
                                                          rows="4">{{ old('content', $post->showHtmlContent) }}</textarea>
                                            </a>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="form-group">
                                                <label>Теги</label>
                                                <select class="form-control select2" multiple
                                                        tabindex="-1"
                                                        name="tags[]">
                                                    @foreach($tags_list as $tag)
                                                        <option value="{{ $tag->id }}"
                                                                {{ $post->tags->contains('id', $tag->id) ? 'selected' : '' }}
                                                        >
                                                            {{ $tag->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button class="btn btn-primary btn-sm" id="btnAddPost">Отправить</button>
                                    </div>
                                </form>

                            </div><!-- /.card-body -->
                        </div>
                        <!-- /.nav-tabs-custom -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection