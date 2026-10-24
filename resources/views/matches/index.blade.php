@extends('layouts.app')

@section('content')
    <div class="row mt-4 mb-5">
        <div class="col-12">
            <h2 class="text-white mb-4">Найденные совпадения</h2>

            @if($matches->isEmpty())
                <div class="alert alert-secondary">Пока совпадений нет. Мы сообщим, если что-то найдется!</div>
            @else
                <div class="row">
                    @foreach($matches as $match)
                        <div class="col-md-6 mb-4">
                            <div class="card p-4 border border-warning h-100 d-flex flex-column">
                                <h5 class="text-warning mb-3">✨ Возможное совпадение!</h5>

                                <div class="row text-center mb-3">
                                    <!-- Блок потерянной вещи (проверяем, есть ли она) -->
                                    <div class="col-6">
                                        <strong class="text-white d-block mb-2">Потеряно:</strong>
                                        @if($match->lostItem)
                                            @if($match->lostItem->photo)
                                                <img src="{{ asset('storage/' . $match->lostItem->photo) }}" class="img-fluid rounded mb-2" style="max-height: 150px; object-fit: cover;">
                                            @endif
                                            <p class="mb-1 text-white">{{ $match->lostItem->title }}</p>
                                            <small class="text-muted">
                                                {{ $match->lostItem->location->city ?? '' }}, {{ $match->lostItem->location->street ?? '' }}
                                            </small>
                                        @else
                                            <div class="bg-dark rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                                                <small class="text-muted">Нет данных</small>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Блок найденной вещи (проверяем, есть ли она) -->
                                    <div class="col-6">
                                        <strong class="text-white d-block mb-2">Найдено:</strong>
                                        @if($match->foundItem)
                                            @if($match->foundItem->photo)
                                                <img src="{{ asset('storage/' . $match->foundItem->photo) }}" class="img-fluid rounded mb-2" style="max-height: 150px; object-fit: cover;">
                                            @endif
                                            <p class="mb-1 text-white">{{ $match->foundItem->title }}</p>
                                            <small class="text-muted">
                                                {{ $match->foundItem->location->city ?? '' }}, {{ $match->foundItem->location->street ?? '' }}
                                            </small>
                                        @else
                                            <div class="bg-dark rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                                                <small class="text-muted">Чат создан вручную</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-auto">
                                    <a href="{{ route('chat', $match->id) }}" class="btn btn-custom-blue w-100 fw-bold">
                                        💬 Перейти в чат
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
