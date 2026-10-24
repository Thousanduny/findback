<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Location;
use App\Models\ItemMatch;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'details' => 'required|string|max:255',
            'type' => 'required|in:lost,found',
            'event_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        $location = Location::create([
            'name' => $request->city . ', ' . $request->street,
            'country' => $request->country,
            'city' => $request->city,
            'street' => $request->street,
            'details' => $request->details,
        ]);

        $item = Item::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'location_id' => $location->id,
            'type' => $request->type,
            'event_date' => $request->event_date,
            'status' => 'active',
            'photo' => $photoPath,
        ]);

        if (Auth::check()) {
            $user = Auth::user();
            $user->rating = ($user->rating ?? 0) + 1;
            $user->save();
        }

        $this->findMatches($item);

        return redirect()->route('items.my')->with('success', 'Объявление успешно добавлено! +1 балл к рейтингу.');
    }

    public function show(Item $item)
    {
        $item->load(['user', 'category', 'location']);
        $existingMatch = null;
        if (Auth::check() && Auth::id() !== $item->user_id) {
            $existingMatch = ItemMatch::where(function($query) use ($item) {
                $query->where('lost_item_id', $item->id)
                      ->whereHas('foundItem', function($q) {
                          $q->where('user_id', Auth::id());
                      });
            })->orWhere(function($query) use ($item) {
                $query->where('found_item_id', $item->id)
                      ->whereHas('lostItem', function($q) {
                          $q->where('user_id', Auth::id());
                      });
            })->first();
        }
        return view('items.show', compact('item', 'existingMatch'));
    }

    public function edit(Item $item)
    {
        if ($item->user_id !== Auth::id()) abort(403);
        if ($item->status === 'returned') return redirect()->route('items.my')->with('error', 'Нельзя редактировать.');
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        if ($item->user_id !== Auth::id()) abort(403);
        if ($item->status === 'returned') return redirect()->route('items.my')->with('error', 'Нельзя редактировать.');

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'details' => 'required|string|max:255',
            'event_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = $item->photo;
        if ($request->hasFile('photo')) {
            if ($item->photo) Storage::disk('public')->delete($item->photo);
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        $item->location->update([
            'name' => $request->city . ', ' . $request->street,
            'country' => $request->country,
            'city' => $request->city,
            'street' => $request->street,
            'details' => $request->details,
        ]);

        $item->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'event_date' => $request->event_date,
            'photo' => $photoPath,
        ]);

        return redirect()->route('items.my')->with('success', 'Объявление обновлено!');
    }

    public function markAsReturned(Item $item)
    {
        if ($item->user_id !== Auth::id()) abort(403);
        $item->update(['status' => 'returned']);

        if (Auth::check()) {
            $user = Auth::user();
            $user->rating = ($user->rating ?? 0) + 10;
            $user->save();
        }

        $match = ItemMatch::where('lost_item_id', $item->id)->orWhere('found_item_id', $item->id)->first();
        if ($match) {
            $pairedItem = ($item->type === 'lost') ? Item::find($match->found_item_id) : Item::find($match->lost_item_id);
            if ($pairedItem) $pairedItem->update(['status' => 'returned']);
        }

        return redirect()->route('items.my')->with('success', 'Вещь возвращена! +10 баллов к рейтингу.');
    }

    // ОБНОВЛЕННЫЙ МЕТОД
    public function startChat(Item $item)
    {
        if (Auth::id() === $item->user_id) {
            return redirect()->back()->with('error', 'Нельзя связаться с самим собой.');
        }

        // Ищем существующий чат между этими двумя пользователями по этому объявлению
        $existingMatch = ItemMatch::where(function($query) use ($item) {
            $query->whereHas('lostItem', function($q) use ($item) {
                $q->where('user_id', $item->user_id);
            })->orWhereHas('foundItem', function($q) use ($item) {
                $q->where('user_id', $item->user_id);
            });
        })->where(function($query) use ($item) {
            $query->where('initiator_id', Auth::id())
                  ->orWhereHas('lostItem', function($q) {
                      $q->where('user_id', Auth::id());
                  })->orWhereHas('foundItem', function($q) {
                      $q->where('user_id', Auth::id());
                  });
        })->first();

        if ($existingMatch) {
            return redirect()->route('chat', $existingMatch->id);
        }

        // Создаем новый чат и записываем, кто его инициировал
        if ($item->type === 'lost') {
            $match = ItemMatch::create([
                'lost_item_id' => $item->id,
                'initiator_id' => Auth::id()
            ]);
        } else {
            $match = ItemMatch::create([
                'found_item_id' => $item->id,
                'initiator_id' => Auth::id()
            ]);
        }

        return redirect()->route('chat', $match->id);
    }

    private function findMatches(Item $newItem)
    {
        $oppositeType = $newItem->type === 'lost' ? 'found' : 'lost';
        $startDate = Carbon::parse($newItem->event_date)->subDays(7);
        $endDate = Carbon::parse($newItem->event_date)->addDays(7);

        $possibleMatches = Item::where('type', $oppositeType)
            ->where('status', 'active')
            ->where('category_id', $newItem->category_id)
            ->whereHas('location', function($query) use ($newItem) {
                $query->where('city', $newItem->location->city)->where('street', $newItem->location->street);
            })
            ->whereBetween('event_date', [$startDate, $endDate])
            ->where('user_id', '!=', Auth::id())
            ->get();

        foreach ($possibleMatches as $match) {
            $exists = ItemMatch::where(function($query) use ($newItem, $match) {
                $query->where('lost_item_id', $newItem->id)->where('found_item_id', $match->id);
            })->orWhere(function($query) use ($newItem, $match) {
                $query->where('lost_item_id', $match->id)->where('found_item_id', $newItem->id);
            })->exists();

            if (!$exists) {
                if ($newItem->type === 'lost') {
                    ItemMatch::create(['lost_item_id' => $newItem->id, 'found_item_id' => $match->id]);
                } else {
                    ItemMatch::create(['lost_item_id' => $match->id, 'found_item_id' => $newItem->id]);
                }
            }
        }
    }

    public function myItems(Request $request)
    {
        $query = Item::where('user_id', Auth::id());
        if ($request->filled('type')) $query->where('type', $request->type);
        if ($request->filled('search')) $query->where('title', 'like', '%' . $request->search . '%');
        if ($request->filled('date')) $query->whereDate('event_date', $request->date);
        if ($request->filled('location')) {
            $loc = $request->location;
            $query->whereHas('location', function($q) use ($loc) {
                $q->where('city', 'like', "%$loc%")->orWhere('street', 'like', "%$loc%")->orWhere('details', 'like', "%$loc%");
            });
        }
        $sort = $request->input('sort', 'newest');
        $query->orderBy('created_at', $sort === 'oldest' ? 'asc' : 'desc');

        $items = $query->paginate(12)->withQueryString();
        return view('items.my', compact('items'));
    }
}
