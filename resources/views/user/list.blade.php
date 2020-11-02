@extends('layouts.app')

@push('scripts')
    <script src="{{ asset('js/users_control/list.js') }}"></script>
@endpush

@section('content')
    <div class="content-wrapper ml-0">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <h5>Пользователи</h5>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="float-right">
                            {{ Breadcrumbs::render('users-control') }}
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>


        <section class="content">
            <div class="card">
                <div class="card-header">
                    <div class="float-right">
                        @can('role-create')
                            <a class="btn btn-success btn-xs" href="{{ route('user.create') }}">
                                <i class="fas fa-plus"></i>
                                Добавить пользователя
                            </a>
                        @endcan
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table table-hover table-valign-middle">
                        <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>login/email</th>
                            <th>имя</th>
                            <th>роль</th>
                            <th>дата регистрации</th>
                            <th>дата активации</th>
                            <th>created_at</th>
                            <th>updated_at</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($usersList as $key => $userItem)
                            <tr>
                                <td>
                                    <div class="btn-group-vertical">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default" data-toggle="dropdown">
                                                <i class="fas fa-user-edit"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('user.show', $userItem->id) }}">Подробно</a></li>
                                                @can('user-edit')
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('user.auth', $userItem->id) }}">
                                                            Авторизоваться
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('user.edit', $userItem->id) }}">
                                                            Редактировать
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('user-delete')
                                                    <li class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item js-delete-user"
                                                           data-id="{{ $userItem->id }}"
                                                           href="{{ route('user.destroy', $userItem->id) }}">Удалить</a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="background-image: url({{ $userItem->avatar }})"
                                         class="img-circle avatar-circle-mini elevation-2 js-user-avatar"></div>
                                </td>
                                <td><a href="{{ route('user.show', $userItem->id) }}">{{ $userItem->email }}</a></td>
                                <td>{{ $userItem->name }}</td>
                                <td>{{ $userItem->role_name }}</td>
                                <td>{{ $userItem->date_registration }}</td>
                                <td>{{ $userItem->date_active }}</td>
                                <td>{{ $userItem->created_at }}</td>
                                <td>{{ $userItem->updated_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    {{ $usersList->links() }}
                </div>
            </div>
        </section>
    </div>

@endsection