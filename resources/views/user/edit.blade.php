@extends('layouts.app')

@push('scripts')
    <script src="{{ asset('js/users_control/edit.js') }}"></script>
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

                                <p class="text-muted text-center js-role-name-value">{{ $userItem->role[0]->name }}</p>

                                @can('user-edit')
                                    <div class="text-center">
                                        <a href="{{ route('user.auth', $userItem->id) }}" class="btn btn-primary btn-xs">Авторизоваться</a>
                                    </div>
                                @endcan
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
                                    <li class="nav-item"><a class="nav-link" href="#control" data-toggle="tab">Управление</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#changePassword" data-toggle="tab">Пароль</a></li>
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

                                        <form action="{{ route('user.update', $userItem->id) }}" method="post" id="formUserUpdate">
                                            @method('PATCH')
                                            @csrf

                                            <ul class="list-group list-group-unbordered mb-3">
                                                <li class="list-group-item">
                                                    <strong>Имя</strong>
                                                    <a class="float-right col-md-10">
                                                        <input type="text" name="name" class="form-control" value="{{ $userItem->name }}"/>
                                                    </a>
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Email</strong>
                                                    <a class="float-right col-md-10">
                                                        <input type="text" name="email" class="form-control" value="{{ $userItem->email }}" required/>
                                                    </a>
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Пол</strong>
                                                    <a class="float-right col-md-10">
                                                        <select name="sex" class="form-control">
                                                            <option value="" {{ !$userItem->sex ? 'selected' : '' }}>Нет</option>
                                                            @foreach($sexList as $sexItem)
                                                                <option value="{{ $sexItem->id }}"
                                                                        {{ $sexItem->id == $userItem->sex ? 'selected' : '' }}>
                                                                    {{ $sexItem->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </a>
                                                </li>


                                                <li class="list-group-item">
                                                    <strong>Дата рождения</strong>
                                                    <a class="float-right col-md-10">
                                                        <input type="text" class="form-control" name="birthday" value="{{ $userItem->birthday }}"/>
                                                    </a>
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Место проживания</strong>
                                                    <a class="float-right col-md-10">
                                                        <input type="text" class="form-control" name="residence" value="{{ $userItem->residence }}"/>
                                                    </a>
                                                </li>

                                                <li class="list-group-item">
                                                    <strong>О себе</strong>
                                                    <a class="d-block">
                                                        <textarea name="description"
                                                                  class="form-control"
                                                                  cols="30"
                                                                  rows="4">{{ $userItem->description }}</textarea>
                                                    </a>
                                                </li>
                                            </ul>

                                            <div class="text-center">
                                                <button class="btn btn-primary btn-sm" id="btnUserUpdate">Сохканить</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.tab-pane -->

                                    <!-- .tab-pane -->
                                    <div class="tab-pane" id="control">
                                        <form method="post"
                                              action="{{ route('user.controlUpdate') }}"
                                              id="formControlUser">
                                            @csrf

                                            <input type="hidden" value="{{ $userItem->id }}" name="user_id">
                                            <div class="form-group">
                                                <label>Роль</label>
                                                <select class="form-control select2-hidden-accessible" tabindex="-1" id="roleUser" name="user_role">
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}" {{ $role->id == $userItem->role[0]->id ? 'selected' : '' }}>
                                                            {{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.tab-pane -->

                                    <!-- .tab-pane -->
                                    <div class="tab-pane" id="changePassword">
                                        <form method="post"
                                              action="{{ route('user.changePassword') }}"
                                              id="formChangePassword">
                                            @csrf

                                            <input type="hidden" value="{{ $userItem->id }}" name="user_id">
                                            <div class="form-group">
                                                <label>Пароль</label>
                                                <input type="password" name="password" class="form-control" placeholder="Пароль">
                                            </div>
                                            <div class="form-group">
                                                <label>Подтвердите пароль</label>
                                                <input type="password" name="password_confirmation" class="form-control" placeholder="Подтвердите пароль">
                                            </div>

                                            <div class="text-center">
                                                <button class="btn btn-primary btn-sm" id="btnChangePassword">Сохканить</button>
                                            </div>
                                        </form>
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