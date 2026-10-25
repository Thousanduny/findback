@extends('layouts.app')

@section('content')
    <div class="row mt-4">
        <div class="col-12">
            <h2 class="text-white mb-4">Мои объявления</h2>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card mb-4 p-4">
                <ul class="nav nav-pills mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ request('type') == '' ? 'active-tab' : 'inactive-tab' }}" href="{{ route('items.my') }}">Все мои</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('type') == 'lost' ? 'active-tab' : 'inactive-tab' }}" href="{{ route('items.my', ['type' => 'lost']) }}">Я потерял</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('type') == 'found' ? 'active-tab' : 'inactive-tab' }}" href="{{ route('items.my', ['type' => 'found']) }}">Я нашел</a>
                    </li>
                </ul>

                <form action="{{ route('items.my') }}" method="GET" class="row g-3">
                    <input type="hidden" name="type" value="{{ request('type') }}">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Поиск по названию..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="location" class="form-control" placeholder="Город, улица..." value="{{ request('location') }}">
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
                            <a href="{{ route('items.my') }}" class="btn btn-outline-secondary ms-2">Сбросить</a>
                        @endif
                    </div>
                </form>
            </div>

            @if($items->isEmpty())
                <div class="alert alert-secondary">Объявлений не найдено.</div>
            @else
                <div class="row">
                    @foreach($items as $item)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 d-flex flex-column {{ $item->status === 'returned' ? 'opacity-75' : '' }}">

                                <a href="{{ route('items.show', $item->id) }}" class="text-decoration-none">
                                    @if($item->photo)
                                        <img src="{{ $item->photo }}" class="card-img-top" alt="Фото" style="height: 200px; object-fit: cover; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                                    @endif

                                    <div class="p-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <h5 class="text-white">{{ $item->title }}</h5>
                                            <div>
                                                <span class="badge {{ $item->type === 'lost' ? 'bg-danger' : 'bg-success' }} d-block mb-1">
                                                    {{ $item->type === 'lost' ? 'Потеряно' : 'Найдено' }}
                                                </span>
                                                @if($item->status === 'returned')
                                                    <span class="badge bg-primary">Возвращено</span>
                                                @endif
                                            </div>
                                        </div>

                                        <p class="text-muted mb-1">
                                            {{ $item->location->city }}, {{ $item->location->street }}
                                            <br>
                                            <small>({{ $item->location->details }})</small>
                                        </p>
                                        <small class="text-secondary mb-2">Дата: {{ \Carbon\Carbon::parse($item->event_date)->format('d.m.Y') }}</small>
                                    </div>
                                </a>

                                <div class="px-3 pb-3 mt-auto">
                                    @if($item->status === 'active')
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-outline-warning w-50">Редактировать</a>

                                            <form action="{{ route('items.return', $item->id) }}" method="POST" class="w-50" onsubmit="return confirm('Вы уверены, что вещь нашлась и вы хотите закрыть объявление?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success w-100">Нашлась!</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
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
