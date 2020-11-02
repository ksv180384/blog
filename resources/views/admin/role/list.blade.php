@extends('layouts.app')


@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif


    <div class="card">
        <div class="card-header">
            <h2 class="card-title font-weight-bold">Управление ролями</h2>
            <div class="float-right">
                @can('role-create')
                    <a class="btn btn-success btn-xs" href="{{ route('roles.create') }}">
                        <i class="fas fa-plus"></i>
                        Слздать роль
                    </a>
                @endcan
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Название</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $key => $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>
                                {{ $role->name }}
                                <div class="float-right">
                                    <a class="btn btn-info btn-xs" href="{{ route('roles.show',$role->id) }}">Подробно</a>
                                    @can('role-edit')
                                        <a class="btn btn-primary btn-xs" href="{{ route('roles.edit',$role->id) }}">Редактировать</a>
                                    @endcan
                                    @can('role-delete')
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="post" class="d-inline-block">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-xs">Удалить</button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
@endsection