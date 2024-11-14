<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NewsService
{
    private $newsApiKey;
    private $openNewsApiKey;
    private $newYarkApiKey;
    private $guardianApiKey;

    public function __construct()
    {
        $this->newsApiKey = env('NEWSAPI_KEY');
        $this->guardianApiKey = env('GUARDIAN_API_KEY');
        $this->openNewsApiKey = env('OPENNEWS_KEY');
        $this->newYarkApiKey = env('NEWYARKTIMS_KEY');
    }

    public function getArticles($filters)
    {
        $country = $filters['country'] ?? 'us';
        $category = $filters['category'] ?? 'general';
        $page = $filters['page'] ?? 1;
        $pageSize = $filters['pageSize'] ?? 10;
        $newsApiUrl = "https://newsapi.org/v2/top-headlines?country={$country}&category={$category}&apiKey={$this->newsApiKey}&page={$page}&pageSize={$pageSize}";
        $newsApiResponse = Http::get($newsApiUrl);
        $guardianApiUrl = "https://content.guardianapis.com/search?q={$category}&api-key={$this->guardianApiKey}&page={$page}&page-size={$pageSize}";
        $guardianApiResponse = Http::get($guardianApiUrl);
        $newYarkApiUrl = "https://api.nytimes.com/svc/topstories/v2/home.json?api-key={$this->newYarkApiKey}";
        $newYarkApiResponse = Http::get($newYarkApiUrl);


        return [
            'newsapi' => $newsApiResponse->json(),
            'guardianapi' => $guardianApiResponse->json(),
            'newYarkApiUrl' => $newYarkApiResponse->json()
        ];
    }
}
