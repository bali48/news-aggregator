<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends Controller
{

    public function updatePreferences(Request $request)
    {
        $data = $request->validate([
            'sources' => 'string',
            'categories' => 'string',
            'authors' => 'string',
        ]);
    
        $user = Auth::user();
    
        $preferences = UserPreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'sources' => $data['sources'] ?? '',
                'categories' => $data['categories'] ?? '',
                'authors' => $data['authors'] ?? '',
            ]
        );
    
        return response()->json(['message' => 'Preferences updated successfully', 'preferences' => $preferences]);
    }
    
    public function getPreferences()
    {
        $user = Auth::user();
        $preferences = UserPreference::where('user_id', $user->id)->first();

        return response()->json(['preferences' => $preferences, 'user' => $user]);
    }

    public function fetchCategoriesAndAuthors()
    {
        $country = $filters['country'] ?? 'us';
        $category = $filters['category'] ?? 'general';
        $newsApiKey = env('NEWSAPI_KEY');
        $guardianApiKey = env('GUARDIAN_API_KEY');

        // NewsAPI URL
        $newsApiUrl = "https://newsapi.org/v2/top-headlines?country={$country}&category={$category}&apiKey={$newsApiKey}";
        $newsApiResponse = Http::get($newsApiUrl);

        // Guardian API URL
        $guardianApiUrl = "https://content.guardianapis.com/search?api-key={$guardianApiKey}";
        $guardianApiResponse = Http::get($guardianApiUrl);
        $categories = [];
        $authors = [];
        $sources = [];


        if ($newsApiResponse->successful()) {
            $articles = $newsApiResponse->json()['articles'] ?? [];
            foreach ($articles as $article) {
                if (isset($article['category'])) {
                    $categories[] = $article['category'];
                }
                if (isset($article['author'])) {
                    $authors[] = $article['author'];
                }
                if (isset($article['source'])) {
                    $sources[] = $article['source'];
                }
            }
        }

        if ($guardianApiResponse->successful()) {
            $results = $guardianApiResponse->json()['response']['results'] ?? [];
            foreach ($results as $result) {
                if (isset($result['sectionName'])) {
                    $categories[] = $result['sectionName'];
                }
                if (isset($result['author'])) {
                    $authors[] = $result['author'];
                }
            }
        }
        $uniqueCategories = array_unique($categories);
        $uniqueAuthors = array_unique($authors);
        $collection = collect($sources);
        $uniqueSources = $collection->whereNotNull('id')->unique('id')->values()->all();

        $categoriesArray = array_map(function ($category, $index) {
            return ['id' => $index + 1, 'name' => $category];
        }, $uniqueCategories, array_keys($uniqueCategories));

        $authorsArray = array_map(function ($author, $index) {
            return ['id' => $index + 1, 'name' => $author];
        }, $uniqueAuthors, array_keys($uniqueAuthors));
        return response()->json([
            'categories' => $categoriesArray,
            'authors' => $authorsArray,
            'sources' => $uniqueSources
        ]);
    }
}
