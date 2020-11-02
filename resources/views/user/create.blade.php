@extends('layouts.app')

@push('scripts')
    <script src="{{ asset('js/users_control/create.js') }}"></script>
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper ml-0">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <h5>Добавить пользователя</h5>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="float-right">
                            {{ Breadcrumbs::render('user-create') }}
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

                                <form action="{{ route('user.store') }}" method="post" id="formUserCreate">
                                    @csrf

                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <strong>Имя</strong>
                                            <a class="float-right col-md-10">
                                                <input type="text" name="name" class="form-control" value="">
                                            </a>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Email</strong>
                                            <a class="float-right col-md-10">
                                                <input type="text" name="email" class="form-control" value="" required>
                                            </a>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Пол</strong>
                                            <a class="float-right col-md-10">
                                                <select name="sex" class="form-control">
                                                    <option value="">Нет</option>
                                                    @foreach($sexList as $sexItem)
                                                        <option value="{{ $sexItem->id }}">
                                                            {{ $sexItem->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </a>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>О себе</strong>
                                            <a class="d-block">
                                                <textarea name="description"
                                                          class="form-control"
                                                          cols="30"
                                                          rows="4"></textarea>
                                            </a>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="form-group">
                                                <label>Роль</label>
                                                <select class="form-control select2-hidden-accessible"
                                                        tabindex="-1"
                                                        id="roleUser"
                                                        name="user_role">
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}">
                                                            {{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </li>

                                        <li class="list-group-item">
                                            <div class="form-group">
                                                <label>Пароль</label>
                                                <input type="password" name="password" class="form-control" placeholder="Пароль">
                                            </div>
                                            <div class="form-group">
                                                <label>Подтвердите пароль</label>
                                                <input type="password" name="password_confirmation" class="form-control" placeholder="Подтвердите пароль">
                                            </div>
                                        </li>
                                    </ul>

                                    <div class="text-center">
                                        <button class="btn btn-primary btn-sm" id="btnUserCreate">Сохранить</button>
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