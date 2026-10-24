<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemMatch;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $matches = ItemMatch::where(function($query) use ($userId) {
            $query->whereHas('lostItem', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->orWhereHas('foundItem', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->orWhere('initiator_id', $userId);
        })->orderBy('updated_at', 'desc')->get();

        $chats = $matches->map(function($match) use ($userId) {
            $opponent = null;
            $activeItem = null;

            if ($match->initiator_id === $userId) {
                $activeItem = $match->lostItem ?? $match->foundItem;
                $opponent = $activeItem ? $activeItem->user : null;
            } elseif ($match->lostItem && $match->lostItem->user_id === $userId) {
                $opponent = User::find($match->initiator_id);
                $activeItem = $match->lostItem;
            } elseif ($match->foundItem && $match->foundItem->user_id === $userId) {
                $opponent = User::find($match->initiator_id);
                $activeItem = $match->foundItem;
            } else {
                if ($match->lostItem) $opponent = $match->lostItem->user;
                if ($match->foundItem && $match->foundItem->user_id !== $userId) $opponent = $match->foundItem->user;
                $activeItem = $match->lostItem ?? $match->foundItem;
            }

            $lastMessage = Message::where('match_id', $match->id)->orderBy('created_at', 'desc')->first();

            return [
                'match' => $match,
                'opponent' => $opponent,
                'activeItem' => $activeItem,
                'lastMessage' => $lastMessage,
            ];
        });

        return view('chat.index', compact('chats'));
    }

    public function show(ItemMatch $match)
    {
        $userId = Auth::id();

        $isParticipant = ($match->lostItem && $match->lostItem->user_id === $userId) ||
                         ($match->foundItem && $match->foundItem->user_id === $userId) ||
                         ($match->initiator_id === $userId);

        if (!$isParticipant) {
            abort(403, 'У вас нет доступа к этому чату.');
        }

        $messages = Message::where('match_id', $match->id)->orderBy('created_at', 'asc')->get();

        $opponent = null;
        $activeItem = null;

        if ($match->initiator_id === $userId) {
            $activeItem = $match->lostItem ?? $match->foundItem;
            $opponent = $activeItem ? $activeItem->user : null;
        } elseif ($match->lostItem && $match->lostItem->user_id === $userId) {
            $opponent = User::find($match->initiator_id);
            $activeItem = $match->lostItem;
        } elseif ($match->foundItem && $match->foundItem->user_id === $userId) {
            $opponent = User::find($match->initiator_id);
            $activeItem = $match->foundItem;
        }

        $lostItem = $match->lostItem;
        $foundItem = $match->foundItem;

        return view('chat.show', compact('match', 'messages', 'opponent', 'lostItem', 'foundItem', 'activeItem'));
    }

    public function store(Request $request, ItemMatch $match)
    {
        $userId = Auth::id();

        $isParticipant = ($match->lostItem && $match->lostItem->user_id === $userId) ||
                         ($match->foundItem && $match->foundItem->user_id === $userId) ||
                         ($match->initiator_id === $userId);

        if (!$isParticipant) {
            abort(403);
        }

        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        Message::create([
            'match_id' => $match->id,
            'sender_id' => $userId,
            'body' => $request->body,
        ]);

        $match->touch();

        return redirect()->route('chat', $match->id);
    }

    // НОВЫЙ МЕТОД: Удаление чата
    public function destroy(ItemMatch $match)
    {
        $userId = Auth::id();

        $isParticipant = ($match->lostItem && $match->lostItem->user_id === $userId) ||
                         ($match->foundItem && $match->foundItem->user_id === $userId) ||
                         ($match->initiator_id === $userId);

        if (!$isParticipant) {
            abort(403, 'У вас нет прав на удаление этого чата.');
        }

        // Удаляем все сообщения этого чата (на всякий случай, если каскадное удаление не настроено)
        Message::where('match_id', $match->id)->delete();

        // Удаляем сам чат
        $match->delete();

        return redirect()->route('chats.index')->with('success', 'Чат успешно удален.');
    }
}
