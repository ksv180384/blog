@extends('layouts.app')


@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-left mr-2">
                <a class="btn btn-primary btn-xs" href="{{ route('roles.index') }}"> Назад</a>
            </div>
            <h2 class="card-title font-weight-bold">Роль</h2>
        </div>

        <div class="card-body">
            <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item border-top-0">
                    <strong>Название:</strong> {{ $role->name }}
                </li>
                <li class="list-group-item border-bottom-0">
                    <dl>
                        <dt class="mb-2">Права:</dt>
                        @if(!empty($rolePermissions))
                            @foreach($rolePermissions as $v)
                                <dd>{{ $v->name }}</dd>
                            @endforeach
                        @endif
                    </dl>
                </li>
            </ul>
        </div>
    </div>
@endsection