@extends('layouts.app')

@push('scripts')
    <script src="{{ asset('js/profile/user.js') }}"></script>
    <script src="{{ asset('js/blog/like.js') }}"></script>
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper ml-0">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <h5>{{ $user_item->email }}</h5>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">

                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <div class="profile-user-img avatar-circle img-fluid img-circle js-user-avatar"
                                         style="background-image: url({{ Storage::url($user_item->avatar) }})"
                                         alt="User profile picture">

                                    </div>
                                </div>

                                <h3 class="profile-username text-center">{{ $user_item->name }}</h3>

                                <p class="text-muted text-center js-role-name-value">{{ $user_item->role_name }}</p>

                                <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item">
                                        <strong>Подписчиков</strong> <a class="float-right" id="followTo">{{ $user_item->followToCount }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Подписан</strong> <a class="float-right">{{ $user_item->followFromCount }}</a>
                                    </li>
                                </ul>

                                @if(Auth::check() && $user_item->id != Auth::id())
                                    @if(!$follow_check)
                                        {{ view('user.profile.btn.btn_follow_add', compact('user_item')) }}
                                    @else
                                        {{ view('user.profile.btn.btn_follow_remove', compact('follow_check')) }}
                                    @endif
                                @endif

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                    </div>
                    <!-- /.col -->
                    <div class="col-md-9">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item"><a class="nav-link active" href="#timeline" data-toggle="tab">Информация</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Статьи</a></li>
                                </ul>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane" id="activity">
                                        @foreach($posts as $post)
                                            {{ view('blog.post.post_list_item', compact('post')) }}
                                        @endforeach

                                        <hr>

                                        {{ $posts->links() }}
                                    </div>
                                    <!-- /.tab-pane -->

                                    <!-- .tab-pane -->
                                    <div class="tab-pane active" id="timeline">

                                        <ul class="list-group list-group-unbordered mb-3">
                                            <li class="list-group-item">
                                                <strong>Email</strong> <a class="float-right">{{ $user_item->email }}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <strong>Пол</strong> <a class="float-right">{{ $user_item->sex_title }}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <strong>О себе</strong> <a class="d-block">{{ $user_item->description }}</a>
                                            </li>
                                        </ul>

                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
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