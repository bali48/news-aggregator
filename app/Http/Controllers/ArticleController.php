<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use App\Services\NewsService;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        // dd("coming here");
        $this->newsService = $newsService;
    }

    public function searchArticles(Request $request)
    {

        $filters = $request->only(['keyword', 'category', 'source', 'from', 'to']);

        $articles = $this->newsService->getArticles($filters);

        return response()->json($articles);
    }

    public function personalizedFeed(Request $request)
    {
        $filters = [];
        $user = Auth::user();
        if ($user) {
            $preferences = UserPreference::where('user_id', $user->id)->first();
            if ($preferences) {
                $sources = is_string($preferences->sources) ? json_decode($preferences->sources, true) : $preferences->sources;
                $categories = is_string($preferences->categories) ? json_decode($preferences->categories, true) : $preferences->categories;
                $authors = is_string($preferences->authors) ? json_decode($preferences->authors, true) : $preferences->authors;
                $filters['sources'] = implode(',', array_column(is_array($sources) ? $sources : [], 'name'));
                $filters['categories'] = implode(',', array_column(is_array($categories) ? $categories : [], 'name'));
                $filters['authors'] = implode(',', array_column(is_array($authors) ? $authors : [], 'name'));
            }
        }


        $articles = $this->newsService->getArticles($filters);

        return response()->json($articles);
    }

}
