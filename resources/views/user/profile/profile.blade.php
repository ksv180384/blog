@extends('layouts.app')

@push('scripts')
    <script src="{{ asset('js/profile/index.js') }}"></script>
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper ml-0">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <h1>Мой профиль</h1>
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
                                         style="background-image: url({{ $user->avatar }})"
                                         alt="User profile picture">

                                    </div>
                                </div>

                                <h3 class="profile-username text-center">{{ $user->name }}</h3>

                                <p class="text-muted text-center">{{ $user->getRoleNames()[0] }}</p>

                                <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item">
                                        <strong>Подписчиков</strong> <a class="float-right">{{ $follow_count }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Подписан</strong> <a class="float-right">{{ $follow_from_count }}</a>
                                    </li>
                                </ul>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-9">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#settings" data-toggle="tab">Управление профилем</a>
                                    </li>
                                </ul>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <!-- .tab-pane -->

                                    <div class="active tab-pane" id="settings">

                                        <div class="row">
                                            <div class="col-md-auto">
                                                <div class="user-add-avatar-block mb-3">
                                                    <form method="post"
                                                          action="{{ route('profile.updateAvatar') }}"
                                                          enctype="multipart/form-data"
                                                          id="formAddUserAvatar">
                                                        @csrf
                                                        <input type="file" id="inputUserAvatar">
                                                        <div class="user-add-avatar js-user-avatar" style="background-image: url({{ $user->avatar }})"></div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <form method="post" action="{{ route('profile.updateProfile') }}" id="formAddUserData" class="">

                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="inputName">Имя</label>
                                                        <input type="text"
                                                               class="form-control"
                                                               name="name"
                                                               id="inputName"
                                                               placeholder="Имя"
                                                               value="{{ $user->name }}"
                                                        >
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputEmail">Email</label>
                                                        <input type="email"
                                                               class="form-control"
                                                               name="email"
                                                               id="inputEmail"
                                                               placeholder="Email"
                                                               value="{{ $user->email }}"
                                                        >
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Ваш пол</label>
                                                        <select class="form-control" id="inputSex" name="sex">
                                                            <option value="">Нет</option>
                                                            @foreach($userSexList as $sex)
                                                                <option value="{{ $sex->id }}"{{ $sex->id == $user->sex ? ' selected' : '' }}>
                                                                    {{ $sex->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputName2">Name</label>
                                                        <input type="text" class="form-control" id="inputName2" placeholder="Name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputDescription">О себе</label>
                                                        <textarea class="form-control"
                                                                  name="description"
                                                                  id="inputDescription"
                                                                  placeholder="О себе">{{ $user->description }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputSkills">Skills</label>
                                                        <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                                                    </div>

                                                    <div class="form-group">
                                                        <button type="submit" id="btnSaveUserData" class="btn btn-primary">Сохранить</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
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