<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use App\Services\NewsService;
use Illuminate\Container\Attributes\Auth;
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
        $user = Auth::user();
        $preferences = UserPreference::where('user_id', $user->id)->first();

        $filters = [];
        if ($preferences) {
            $filters['sources'] = implode(',', $preferences->sources ?? []);
            $filters['categories'] = implode(',', $preferences->categories ?? []);
            $filters['authors'] = implode(',', $preferences->authors ?? []);
        }

        $articles = $this->newsService->getArticles($filters);

        return response()->json($articles);
    }
}
