<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemMatch;
use Illuminate\Support\Facades\Auth;

class MatchController extends Controller
{
    public function index()
    {
        $matches = ItemMatch::whereHas('lostItem', function($query) {
            $query->where('user_id', Auth::id());
        })->orWhereHas('foundItem', function($query) {
            $query->where('user_id', Auth::id());
        })->get();

        return view('matches.index', compact('matches'));
    }
}
