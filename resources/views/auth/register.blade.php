@extends('layouts.app')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="card p-4">
                <h2 class="text-center mb-4 text-white">Регистрация</h2>
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Имя</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Пароль</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Повторите пароль</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-custom-orange w-100 fw-bold">Зарегистрироваться</button>
                </form>
            </div>
        </div>
    </div>
@endsection
