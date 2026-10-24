@extends('layouts.app')

@section('content')
    <div class="row justify-content-center mt-5 mb-5">
        <div class="col-md-7">
            <div class="card p-4">
                <h2 class="text-center mb-4 text-white">Редактировать объявление</h2>

                <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Название вещи</label>
                        <input type="text" name="title" class="form-control" value="{{ $item->title }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Категория</label>
                        <select name="category_id" class="form-select form-control" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="border-secondary">
                    <h5 class="text-white mb-3">Где это произошло?</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Страна</label>
                            <input type="text" name="country" class="form-control" value="{{ $item->location->country }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Город</label>
                            <input type="text" name="city" class="form-control" value="{{ $item->location->city }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Улица (или район)</label>
                        <input type="text" name="street" class="form-control" value="{{ $item->location->street }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Конкретное место</label>
                        <input type="text" name="details" class="form-control" value="{{ $item->location->details }}" required>
                    </div>
                    <hr class="border-secondary">

                    <div class="mb-3">
                        <label class="form-label">Дата события</label>
                        <input type="date" name="event_date" class="form-control" value="{{ \Carbon\Carbon::parse($item->event_date)->format('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Фотография вещи</label>
                        @if($item->photo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $item->photo) }}" alt="Текущее фото" style="max-height: 100px; border-radius: 8px;">
                            </div>
                        @endif
                        <input type="file" name="photo" class="form-control" accept="image/*">
                        <small class="text-muted">Оставьте пустым, чтобы не менять фото</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Описание (приметы вещи)</label>
                        <textarea name="description" class="form-control" rows="3">{{ $item->description }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-custom-orange w-100 fw-bold">Сохранить изменения</button>
                </form>
            </div>
        </div>
    </div>
@endsection
