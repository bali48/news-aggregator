<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NewsService
{
    private $newsApiKey;
    private $openNewsApiKey;
    private $newsCredApiKey;
    private $guardianApiKey;

    public function __construct()
    {
        $this->newsApiKey = env('NEWSAPI_KEY');
        $this->guardianApiKey = env('GUARDIAN_API_KEY');
        $this->openNewsApiKey = env('OPENNEWS_KEY');
        $this->newsCredApiKey = env('NEWSCRED_KEY');
    }

    // Fetch articles based on filters
    public function getArticles($filters)
    {
        $queryParams = $filters ? http_build_query($filters): 'latest';
        // dd(  $this->guardianApiKey );
        // Fetch data from NewsAPI
        $newsApiResponse = Http::get("https://newsapi.org/v2/everything?q={$queryParams}&apiKey={$this->newsApiKey}");
        $guardianApiResponse = Http::get("https://content.guardianapis.com/search?q={$queryParams}&api-key={$this->guardianApiKey}");

        // Fetch data from OpenNews (example endpoint)
        // $openNewsResponse = Http::get("https://api.opennews.com/v1/articles?{$queryParams}&apiKey={$this->openNewsApiKey}");

        // // Fetch data from NewsCred (example endpoint)
        // $newsCredResponse = Http::get("https://api.newscred.com/v1/articles?{$queryParams}&apiKey={$this->newsCredApiKey}");

        // Return merged results from all APIs
        return [
            // 'newsapi' => $newsApiResponse->json(),
            'guardianapi' => $guardianApiResponse->json(),
            // 'opennews' => $openNewsResponse->json(),
            // 'newscred' => $newsCredResponse->json(),
        ];
    }
}
