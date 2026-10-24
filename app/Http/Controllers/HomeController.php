<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Location;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Начинаем запрос: только активные объявления
        $query = Item::where('status', 'active');

        // 2. Фильтр по типу (Потеряно / Найдено)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // 3. Поиск по названию
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // 4. Поиск по дате
        if ($request->filled('date')) {
            $query->whereDate('event_date', $request->date);
        }

        // 5. Поиск по локации (город, улица или конкретное место)
        if ($request->filled('location')) {
            $locationSearch = $request->location;
            $query->whereHas('location', function($q) use ($locationSearch) {
                $q->where('city', 'like', '%' . $locationSearch . '%')
                  ->orWhere('street', 'like', '%' . $locationSearch . '%')
                  ->orWhere('details', 'like', '%' . $locationSearch . '%');
            });
        }

        // 6. Сортировка
        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Получаем результаты
        $items = $query->paginate(12)->withQueryString();
        $categories = Category::all(); // Если понадобятся для фильтров

        return view('home.index', compact('items', 'categories'));
    }
}
