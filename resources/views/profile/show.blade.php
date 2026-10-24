@extends('layouts.app')

@section('content')
    <div class="row justify-content-center mt-4 mb-5">
        <div class="col-md-8">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mb-3">← Назад</a>

            <div class="card p-4">
                <div class="d-flex align-items-center mb-4">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ mb_substr($user->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h2 class="text-white mb-0">{{ $user->name }}</h2>
                        <x-rating-badge :rating="$user->rating" />
                    </div>
                </div>

                <!-- Статистика с подписями -->
                <h5 class="text-white mb-3">Статистика</h5>
                <div class="row text-center mb-4">
                    <div class="col-6 col-md-3 mb-2">
                        <div class="bg-dark rounded p-3 border border-secondary h-100 d-flex flex-column justify-content-center">
                            <h3 class="text-danger mb-1">{{ $stats['lost_total'] }}</h3>
                            <small class="text-white" style="font-size: 0.65rem;">Потеряно</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <div class="bg-dark rounded p-3 border border-secondary h-100 d-flex flex-column justify-content-center">
                            <h3 class="text-success mb-1">{{ $stats['found_and_returned'] }}</h3>
                            <small class="text-white" style="font-size: 0.65rem;">Нашел и вернул</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <div class="bg-dark rounded p-3 border border-secondary h-100 d-flex flex-column justify-content-center">
                            <h3 class="text-warning mb-1">{{ $stats['returned_to_owner'] }}</h3>
                            <small class="text-white" style="font-size: 0.65rem;">Вернул хозяину</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <div class="bg-dark rounded p-3 border border-secondary h-100 d-flex flex-column justify-content-center">
                            <h3 class="text-info mb-1">{{ $stats['returned_to_me'] }}</h3>
                            <small class="text-white" style="font-size: 0.65rem;">Вернули мне</small>
                        </div>
                    </div>
                </div>

                <hr class="border-secondary">

                <h5 class="text-white mb-3">Активные объявления</h5>
                <div class="row">
                    @forelse($user->items()->where('status', 'active')->get() as $item)
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('items.show', $item->id) }}" class="text-decoration-none">
                                <div class="card p-2">
                                    <h6 class="text-white mb-1">{{ $item->title }}</h6>
                                    <small class="{{ $item->type === 'lost' ? 'text-danger' : 'text-success' }}">
                                        {{ $item->type === 'lost' ? 'Потеряно' : 'Найдено' }}
                                    </small>
                                </div>
                            </a>
                        </div>
                    @empty
                        <p class="text-muted">Нет активных объявлений.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
