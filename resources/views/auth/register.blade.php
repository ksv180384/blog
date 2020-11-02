@extends('layouts.auth')

@section('content')
    <div class="register-box">
        <div class="register-logo">
            <a href="/"><strong>Good-Guild</strong></a>
        </div>

        <div class="card">
            <div class="card-body register-card-body">


                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input id="email"
                               type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="Email"
                               required
                               autocomplete="name"
                               autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input id="password"
                               type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password" placeholder="Пароль"
                               required
                               autocomplete="new-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input id="password-confirm"
                               type="password"
                               class="form-control"
                               name="password_confirmation"
                               placeholder="Подтвердите пароль"
                               required
                               autocomplete="new-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="icheck-primary">
                                <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                                <label for="agreeTerms">
                                    Я принимаю <a href="#">условия</a>
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary btn-block">Зарегистрироваться</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>


                <div class="mt-5">
                    <a href="{{ route('login') }}" class="text-center">Авторизоваться</a>
                </div>
            </div>
            <!-- /.form-box -->
        </div><!-- /.card -->
    </div>
    <!-- /.register-box -->
@endsection
