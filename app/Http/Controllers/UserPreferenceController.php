<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends Controller
{
    public function updatePreferences(Request $request)
    {
        $data = $request->validate([
            'sources' => 'array',
            'categories' => 'array',
            'authors' => 'array',
        ]);

        $user = Auth::user();
        
        $preferences = UserPreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'sources' => $data['sources'] ?? [],
                'categories' => $data['categories'] ?? [],
                'authors' => $data['authors'] ?? [],
            ]
        );

        return response()->json(['message' => 'Preferences updated successfully', 'preferences' => $preferences]);
    }

    public function getPreferences()
    {
        $user = Auth::user();
        $preferences = UserPreference::where('user_id', $user->id)->first();

        return response()->json(['preferences' => $preferences]);
    }
}
