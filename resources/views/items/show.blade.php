@extends('layouts.app')

@section('content')
    <div class="row justify-content-center mt-5 mb-5">
        <div class="col-md-9">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mb-3">← Назад</a>

            <div class="card overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-5">
                        @if($item->photo)
                            <img src="{{ asset('storage/' . $item->photo) }}" class="img-fluid h-100 w-100" alt="Фото" style="object-fit: cover; min-height: 300px;">
                        @else
                            <div class="bg-secondary d-flex align-items-center justify-content-center h-100" style="min-height: 300px;">
                                <span class="text-muted fs-4">Нет фотографии</span>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-7">
                        <div class="p-4 d-flex flex-column h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h2 class="text-white fw-bold">{{ $item->title }}</h2>
                                <span class="badge {{ $item->type === 'lost' ? 'bg-danger' : 'bg-success' }} fs-6">
                                    {{ $item->type === 'lost' ? 'Потеряно' : 'Найдено' }}
                                </span>
                            </div>

                            <h5 class="text-warning mb-4">{{ $item->category->name }}</h5>

                            <div class="mb-4">
                                <p class="text-light mb-1"><strong>📍 Место:</strong> {{ $item->location->country }}, {{ $item->location->city }}, {{ $item->location->street }}</p>
                                <p class="text-light mb-1"><strong>🌳 Точное место:</strong> {{ $item->location->details }}</p>
                                <p class="text-light mb-1"><strong>📅 Дата:</strong> {{ \Carbon\Carbon::parse($item->event_date)->format('d.m.Y') }}</p>
                            </div>

                            @if($item->description)
                                <div class="mb-4">
                                    <h6 class="text-white">Описание:</h6>
                                    <p class="text-muted">{{ $item->description }}</p>
                                </div>
                            @endif

                            <hr class="border-secondary mt-auto">

                            <div class="mt-3">
                                <!-- Ссылка на профиль автора -->
                                <a href="{{ route('profile.show', $item->user->id) }}" class="text-decoration-none">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                            {{ mb_substr($item->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="text-white mb-0">{{ $item->user->name }}</h6>
                                            <x-rating-badge :rating="$item->user->rating" />
                                        </div>
                                    </div>
                                </a>

                                @auth
                                    @if(Auth::id() !== $item->user_id && $item->status === 'active')
                                        @if($existingMatch)
                                            <a href="{{ route('chat', $existingMatch->id) }}" class="btn btn-success w-100 py-2 fw-bold">
                                                💬 Перейти в чат (совпадение найдено)
                                            </a>
                                        @else
                                            <form action="{{ route('chat.start', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-custom-orange w-100 py-2 fw-bold">
                                                    ✉️ Связаться с {{ $item->type === 'lost' ? 'нашедшим' : 'владельцем' }}
                                                </button>
                                            </form>
                                        @endif
                                    @elseif(Auth::id() === $item->user_id)
                                        <div class="alert alert-secondary text-center py-2 mb-0">Это ваше объявление</div>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-custom-blue w-100 py-2 fw-bold">Войдите, чтобы связаться</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
