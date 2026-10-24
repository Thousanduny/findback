@extends('layouts.app')

@section('content')
    <div class="row justify-content-center mt-5 mb-5">
        <div class="col-md-7">
            <div class="card p-4">
                <h2 class="text-center mb-4 text-white">Добавить объявление</h2>

                <!-- Обязательно добавляем enctype для загрузки файлов -->
                <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Что вы хотите сделать?</label>
                        <select name="type" class="form-select form-control" required>
                            <option value="lost" {{ request('type') == 'lost' ? 'selected' : '' }}>Я потерял вещь</option>
                            <option value="found" {{ request('type') == 'found' ? 'selected' : '' }}>Я нашел вещь</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Название вещи</label>
                        <input type="text" name="title" class="form-control" placeholder="Например: Черный кошелек" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Категория</label>
                        <select name="category_id" class="form-select form-control" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="border-secondary">
                    <h5 class="text-white mb-3">Где это произошло?</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Страна</label>
                            <input type="text" name="country" class="form-control" placeholder="Россия" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Город</label>
                            <input type="text" name="city" class="form-control" placeholder="Москва" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Улица (или район)</label>
                        <input type="text" name="street" class="form-control" placeholder="ул. Ленина" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Конкретное место (приметы)</label>
                        <input type="text" name="details" class="form-control" placeholder="Под деревом возле стены, на лавочке" required>
                    </div>
                    <hr class="border-secondary">

                    <div class="mb-3">
                        <label class="form-label">Дата события</label>
                        <input type="date" name="event_date" class="form-control" required>
                    </div>

                    <!-- НОВОЕ ПОЛЕ ДЛЯ ФОТО -->
                    <div class="mb-3">
                        <label class="form-label">Фотография вещи (необязательно)</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Описание (приметы вещи)</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-custom-orange w-100 fw-bold">Опубликовать</button>
                </form>
            </div>
        </div>
    </div>
@endsection
