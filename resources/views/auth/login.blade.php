@extends('layouts.app')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="card p-4">
                <h2 class="text-center mb-4 text-white">Вход</h2>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Пароль</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-custom-blue w-100 fw-bold">Войти</button>
                </form>
            </div>
        </div>
    </div>
@endsection
