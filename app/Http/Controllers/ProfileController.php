<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Cloudinary\Cloudinary;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $stats = $this->getStats($user);
        return view('profile.index', compact('user', 'stats'));
    }

    public function show(User $user)
    {
        $stats = $this->getStats($user);
        return view('profile.show', compact('user', 'stats'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
        ];

        // Обработка загрузки аватарки в Cloudinary
        if ($request->hasFile('avatar')) {
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => 'vjoiaqxc',
                    'api_key'    => '733256525395747',
                    'api_secret' => '5vukJ1F7yFh42E1wRxpKnQHbpNE',
                ],
            ]);
            $uploadedFileUrl = $cloudinary->uploadApi()->upload($request->file('avatar')->getRealPath())['secure_url'];
            $data['avatar'] = $uploadedFileUrl;
        }

        $user->update($data);

        return redirect()->route('profile')->with('success', 'Профиль обновлен!');
    }

    private function getStats(User $user)
    {
        return [
            'lost_total' => Item::where('user_id', $user->id)->where('type', 'lost')->count(),
            'found_and_returned' => Item::where('user_id', $user->id)->where('type', 'found')->where('status', 'returned')->count(),
            'returned_to_owner' => Item::where('user_id', $user->id)->where('type', 'found')->where('status', 'returned')->count(),
            'returned_to_me' => Item::where('user_id', $user->id)->where('type', 'lost')->where('status', 'returned')->count(),
        ];
    }
}
