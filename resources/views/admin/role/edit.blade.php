@extends('layouts.app')

@push('scripts')
    <script src="{{ asset('js/role/edit.js') }}"></script>
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-left mr-2">
                <a class="btn btn-primary btn-xs" href="{{ route('roles.index') }}"> Назад</a>
            </div>
            <h2 class="card-title font-weight-bold">Добавление новой роли</h2>
        </div>

        <div class="card-body">

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form action="{{ route('roles.update', $role->id) }}" method="post" id="formRoleUpdate">
                @method('PATCH')
                @csrf
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Название:</strong>
                            <input type="text" name="name" placeholder="Название" class="form-control" value="{{ old('name', $role->name) }}">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <dl>
                            <dt class="mb-2">Права:</dt>
                            @foreach($permission as $value)
                                <dd>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input"
                                               type="checkbox"
                                               name="permission[]"
                                               id="customCheckbox{{ $value->id }}"
                                               value="{{ $value->id }}"
                                                {{ in_array($value->id, $rolePermissions) ? 'checked' : '' }}>
                                        <label for="customCheckbox{{ $value->id }}" class="custom-control-label">{{ $value->name }}</label>
                                    </div>
                                </dd>
                            @endforeach
                        </dl>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button id="btnFormRoleUpdate" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
@endsection