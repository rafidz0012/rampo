<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NontonController extends Controller
{
    private $baseUrl = 'https://zeldvorik.ru/apiv3/api.php';

    /**
     * Categories available for the Nonton feature
     */
    private $categories = [
        'trending' => 'Trending',
        'indonesian-movies' => 'Film Indonesia',
        'indonesian-drama' => 'Drama Indonesia',
        'kdrama' => 'K-Drama',
        'short-tv' => 'Short TV',
        'anime' => 'Anime',
        'adult-comedy' => 'Canda Dewasa',
        'western-tv' => 'Western TV',
        'indo-dub' => 'Indo Dub',
    ];

    /**
     * Homepage with trending content and all categories
     */
    public function index(Request $request)
    {
        $activeCategory = $request->get('category', null);
        $page = $request->get('page', 1);

        // For AJAX requests (category load or infinite scroll)
        if ($request->ajax() && $activeCategory) {
            $content = $this->getApiData($activeCategory, ['page' => $page]);
            return response()->json([
                'items' => $content['items'] ?? [],
                'hasMore' => $content['hasMore'] ?? false,
                'page' => $content['page'] ?? $page,
            ]);
        }

        // Get trending for hero banner
        $trending = $this->getApiData('trending', ['page' => 1]);

        // Fetch all categories for homepage rows
        $categoryData = [];
        foreach ($this->categories as $key => $label) {
            $data = $this->getApiData($key, ['page' => 1]);
            $categoryData[$key] = [
                'label' => $label,
                'items' => $data['items'] ?? [],
                'hasMore' => $data['hasMore'] ?? false,
            ];
        }

        return view('nonton.index', [
            'trending' => $trending['items'] ?? [],
            'categories' => $this->categories,
            'categoryData' => $categoryData,
        ]);
    }

    /**
     * Handle search requests
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $page = $request->get('page', 1);

        if (empty($query)) {
            if ($request->ajax()) {
                return response()->json(['items' => [], 'hasMore' => false]);
            }
            return redirect()->route('nonton.index');
        }

        $results = $this->getApiData('search', ['q' => $query]);

        if ($request->ajax()) {
            return response()->json([
                'items' => $results['items'] ?? [],
                'hasMore' => $results['hasMore'] ?? false,
            ]);
        }

        return view('nonton.search', [
            'query' => $query,
            'results' => $results['items'] ?? [],
            'categories' => $this->categories,
            'hasMore' => $results['hasMore'] ?? false,
        ]);
    }

    /**
     * Show content by category
     */
    public function category(Request $request, $category)
    {
        $page = $request->get('page', 1);

        if (!array_key_exists($category, $this->categories)) {
            abort(404);
        }

        $content = $this->getApiData($category, ['page' => $page]);

        if ($request->ajax()) {
            return response()->json([
                'items' => $content['items'] ?? [],
                'hasMore' => $content['hasMore'] ?? false,
                'page' => $content['page'] ?? $page,
            ]);
        }

        return view('nonton.index', [
            'trending' => [],
            'content' => $content['items'] ?? [],
            'categories' => $this->categories,
            'activeCategory' => $category,
            'hasMore' => $content['hasMore'] ?? false,
            'currentPage' => $content['page'] ?? $page,
        ]);
    }

    /**
     * Show detail page for movie/series
     */
    public function detail(Request $request, $detailPath)
    {
        $response = $this->getApiData('detail', ['detailPath' => $detailPath]);

        if (!$response || !isset($response['success']) || !$response['success']) {
            abort(404);
        }

        // Extract data from the 'data' key
        // If data is null/empty but success is true, it might be an issue with the API or ID
        $detail = $response['data'] ?? [];

        return view('nonton.detail', [
            'detail' => $detail,
            'categories' => $this->categories,
            'detailPath' => $detailPath,
        ]);
    }

    /**
     * Show watch page with player
     */
    public function watch(Request $request, $detailPath)
    {
        $response = $this->getApiData('detail', ['detailPath' => $detailPath]);

        if (!$response || !isset($response['success']) || !$response['success']) {
            abort(404);
        }

        $detail = $response['data'] ?? [];
        $episodeUrl = $request->get('episode');

        // If no episode URL provided, try to find the first one or use default playerUrl
        if (!$episodeUrl) {
            $episodeUrl = $detail['playerUrl'] ?? '';
            
            // If it's a series, try to get first episode of first season
            if (empty($episodeUrl) && isset($detail['seasons'][0]['episodes'][0]['playerUrl'])) {
                $episodeUrl = $detail['seasons'][0]['episodes'][0]['playerUrl'];
            }
        }

        return view('nonton.watch', [
            'detail' => $detail,
            'activeEpisodeUrl' => $episodeUrl,
            'detailPath' => $detailPath,
        ]);
    }

    /**
     * Private helper for API calls with caching
     */
    private function getApiData($action, $params = [])
    {
        $queryParams = array_merge(['action' => $action], $params);
        $cacheKey = 'nonton_' . md5(json_encode($queryParams));

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($queryParams) {
            try {
                $response = Http::timeout(10)->get($this->baseUrl, $queryParams);

                if ($response->successful()) {
                    return $response->json();
                }

                return ['success' => false, 'items' => []];
            } catch (\Exception $e) {
                \Log::error('Nonton API Error: ' . $e->getMessage());
                return ['success' => false, 'items' => []];
            }
        });
    }
}
