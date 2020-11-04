@extends('layouts.app')

@push('scripts')
    <script src="{{ asset('js/users_control/user.js') }}"></script>
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper ml-0">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <h5>{{ $userItem->email }}</h5>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="float-right">
                            {{ Breadcrumbs::render('user', $userItem) }}
                        </div>
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
                                         style="background-image: url({{ $userItem->avatar }})"
                                         alt="User profile picture">

                                    </div>
                                </div>

                                <h3 class="profile-username text-center">{{ $userItem->name }}</h3>

                                <p class="text-muted text-center js-role-name-value">{{ $userItem->role_name }}</p>

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
                                        @foreach($userPosts as $post)
                                            {{ view('blog.post.post_list_item', compact('post', 'tags_to_post')) }}
                                        @endforeach

                                        <hr>

                                        {{ $userPosts->links() }}
                                    </div>
                                    <!-- /.tab-pane -->

                                    <!-- .tab-pane -->
                                    <div class="tab-pane active" id="timeline">

                                        <ul class="list-group list-group-unbordered mb-3">
                                            <li class="list-group-item">
                                                <strong>Email</strong> <a class="float-right">{{ $userItem->email }}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <strong>Пол</strong> <a class="float-right">{{ $userItem->sex_title }}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <strong>О себе</strong> <a class="d-block">{{ $userItem->description }}</a>
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