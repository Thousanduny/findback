@extends('layouts.app')

@section('content')
    <div class="row justify-content-center mt-4 mb-5">
        <div class="col-md-8">
            <a href="{{ route('chats.index') }}" class="btn btn-outline-secondary mb-3">← Ко всем чатам</a>

            <div class="card overflow-hidden">
                <div class="bg-dark p-3 border-bottom border-secondary d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        @if($opponent && $opponent->avatar)
                            <img src="{{ $opponent->avatar }}" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                        @else
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                {{ mb_substr($opponent->name ?? '?', 0, 1) }}
                            </div>
                        @endif

                        <div>
                            <h5 class="text-white mb-0">
                                <a href="{{ $opponent ? route('profile.show', $opponent->id) : '#' }}" class="text-white text-decoration-none">
                                    {{ $opponent->name ?? 'Пользователь' }}
                                </a>
                            </h5>
                            @if($opponent)
                                <x-rating-badge :rating="$opponent->rating" />
                            @else
                                <small class="text-secondary">Рейтинг неизвестен</small>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="text-end">
                            @if($lostItem) <span class="badge bg-danger mb-1 d-block">Потеряно: {{ $lostItem->title }}</span> @endif
                            @if($foundItem) <span class="badge bg-success d-block">Найдено: {{ $foundItem->title }}</span> @endif
                        </div>

                        <form action="{{ route('chat.destroy', $match->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот чат? Все сообщения будут потеряны.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Удалить чат">
                                🗑️
                            </button>
                        </form>
                    </div>
                </div>

                <div class="p-4" style="height: 400px; overflow-y: auto; background-color: #111827;">
                    @if($messages->isEmpty())
                        <div class="text-center text-muted mt-5">
                            <p>Сообщений пока нет. Начните общение!</p>
                        </div>
                    @else
                        @foreach($messages as $message)
                            @php
                                $isMine = $message->sender_id === Auth::id();
                                $sender = $isMine ? Auth::user() : $opponent;
                            @endphp

                            <div class="d-flex mb-3 {{ $isMine ? 'justify-content-end' : 'justify-content-start' }}">
                                @if(!$isMine)
                                    @if($sender && $sender->avatar)
                                        <img src="{{ $sender->avatar }}" class="rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover;">
                                    @else
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-2" style="width: 35px; height: 35px; font-size: 0.9rem;">
                                            {{ mb_substr($sender->name ?? '?', 0, 1) }}
                                        </div>
                                    @endif
                                @endif

                                <div class="p-2 rounded" style="max-width: 70%; {{ $isMine ? 'background-color: #2563EB; color: white;' : 'background-color: #374151; color: white;' }}">
                                    <p class="mb-0">{{ $message->body }}</p>
                                    <small class="d-block text-end mt-1" style="font-size: 0.7rem; opacity: 0.7;">
                                        {{ $message->created_at->format('H:i') }}
                                    </small>
                                </div>

                                @if($isMine)
                                    @if($sender && $sender->avatar)
                                        <img src="{{ $sender->avatar }}" class="rounded-circle ms-2" style="width: 35px; height: 35px; object-fit: cover;">
                                    @else
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold ms-2" style="width: 35px; height: 35px; font-size: 0.9rem;">
                                            {{ mb_substr($sender->name ?? '?', 0, 1) }}
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="p-3 bg-dark border-top border-secondary">
                    <form action="{{ route('chat', $match->id) }}" method="POST" class="d-flex gap-2" id="chatForm">
                        @csrf
                        <input type="text" name="body" class="form-control" placeholder="Напишите сообщение..." required autocomplete="off" id="messageInput">
                        <button type="submit" class="btn btn-custom-orange px-4" id="sendButton">Отправить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('chatForm');
            const sendButton = document.getElementById('sendButton');
            const messageInput = document.getElementById('messageInput');

            form.addEventListener('submit', function() {
                sendButton.disabled = true;
                sendButton.textContent = 'Отправка...';
                messageInput.value = '';
            });
        });
    </script>
@endsection
