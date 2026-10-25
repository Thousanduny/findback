@extends('layouts.app')

@section('content')
    <div class="row justify-content-center mt-4 mb-5">
        <div class="col-md-8">
            <h2 class="text-white mb-4">Мои чаты</h2>

            @if($chats->isEmpty())
                <div class="alert alert-secondary">У вас пока нет активных чатов. Найдите объявление и нажмите «Связаться».</div>
            @else
                <div class="list-group">
                    @foreach($chats as $chat)
                        <a href="{{ route('chat', $chat['match']->id) }}" class="list-group-item list-group-item-action bg-dark text-white border-secondary mb-2 rounded d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                @if($chat['opponent'] && $chat['opponent']->avatar)
                                    <img src="{{ $chat['opponent']->avatar }}" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                        {{ mb_substr($chat['opponent']->name ?? '?', 0, 1) }}
                                    </div>
                                @endif

                                <div>
                                    <h6 class="mb-0 text-warning">{{ $chat['opponent']->name ?? 'Пользователь' }}</h6>
                                    <small class="text-muted">
                                        По объявлению: <span class="text-light">{{ $chat['activeItem']->title ?? 'Неизвестно' }}</span>
                                        ({{ $chat['activeItem']->type === 'lost' ? 'Потеряно' : 'Найдено' }})
                                    </small>
                                    @if($chat['lastMessage'])
                                        <div class="text-secondary text-truncate" style="max-width: 400px; font-size: 0.9rem;">
                                            {{ $chat['lastMessage']->body }}
                                        </div>
                                    @else
                                        <div class="text-secondary fst-italic" style="font-size: 0.9rem;">Нет сообщений</div>
                                    @endif
                                </div>
                            </div>

                            <div class="text-end">
                                @if($chat['lastMessage'])
                                    <small class="text-muted">{{ $chat['lastMessage']->created_at->format('d.m H:i') }}</small>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
