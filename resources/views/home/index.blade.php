@extends('layouts.app')

@section('content')
    <!-- Баннер -->
    <div class="hero-graphite text-center mb-5">
        <h1 class="display-4 fw-bold text-white">Потеряй. Найди. Верни.</h1>
        <p class="lead text-light mt-3 mb-5">
            FindBack — это бюро находок, которое работает само.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('items.create') }}?type=lost" class="btn btn-custom-orange btn-lg px-5 py-3">Я потерял вещь</a>
            <a href="{{ route('items.create') }}?type=found" class="btn btn-custom-blue btn-lg px-5 py-3">Я нашел вещь</a>
        </div>
    </div>

    <!-- Вкладки и Поиск -->
    <div class="card mb-4 p-4">
        <ul class="nav nav-pills mb-4">
            <li class="nav-item">
                <a class="nav-link {{ request('type') == '' ? 'active-tab' : 'inactive-tab' }}" href="{{ route('home') }}">Все</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('type') == 'lost' ? 'active-tab' : 'inactive-tab' }}" href="{{ route('home', ['type' => 'lost']) }}">Потеряшки</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('type') == 'found' ? 'active-tab' : 'inactive-tab' }}" href="{{ route('home', ['type' => 'found']) }}">Находки</a>
            </li>
        </ul>

        <form action="{{ route('home') }}" method="GET" class="row g-3">
            <input type="hidden" name="type" value="{{ request('type') }}">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Поиск по названию..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="location" class="form-control" placeholder="Город, улица, место..." value="{{ request('location') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-2">
                <select name="sort" class="form-select form-control">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Сначала новые</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Сначала старые</option>
                </select>
            </div>
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-custom-blue px-4">Найти</button>
                @if(request()->anyFilled(['search', 'location', 'date', 'type']))
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary ms-2">Сбросить</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Лента объявлений -->
    <div class="row mb-5">
        <div class="col-12">
            @if($items->isEmpty())
                <div class="alert alert-secondary">По вашему запросу ничего не найдено.</div>
            @else
                <div class="row">
                    @foreach($items as $item)
                        <div class="col-md-3 mb-4">
                            <a href="{{ route('items.show', $item->id) }}" class="text-decoration-none">
                                <div class="card h-100">
                                    @if($item->photo)
                                        <img src="{{ asset('storage/' . $item->photo) }}" class="card-img-top" alt="Фото" style="height: 180px; object-fit: cover; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                                    @else
                                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 180px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                                            <span class="text-muted">Нет фото</span>
                                        </div>
                                    @endif

                                    <div class="p-3">
                                        <!-- Название вещи и статус -->
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="text-white mb-0">{{ $item->title }}</h6>
                                            <span class="badge {{ $item->type === 'lost' ? 'bg-danger' : 'bg-success' }} ms-2">
                                                {{ $item->type === 'lost' ? 'Потеряно' : 'Найдено' }}
                                            </span>
                                        </div>

                                        <!-- Категория -->
                                        <small class="text-muted d-block mb-2">{{ $item->category->name }}</small>

                                        <!-- Блок с автором (имя сверху, рейтинг снизу) -->
                                        <div class="d-flex flex-column mb-2">
                                            <small class="text-light fw-bold">{{ $item->user->name }}</small>
                                            <x-rating-badge :rating="$item->user->rating" />
                                        </div>

                                        <!-- Локация -->
                                        <small class="text-secondary">{{ $item->location->city }}, {{ $item->location->street }}</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $items->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection
