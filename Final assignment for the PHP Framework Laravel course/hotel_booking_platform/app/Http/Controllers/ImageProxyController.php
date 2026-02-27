<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ImageProxyController extends Controller
{
    public function show($fileId)
    {
        // Check cache first
        $cacheKey = 'image_' . $fileId;
        $cached = \Cache::get($cacheKey);
        
        if ($cached) {
            return response($cached['body'])
                ->header('Content-Type', $cached['type'])
                ->header('Cache-Control', 'public, max-age=31536000');
        }
        
        $url = "https://drive.google.com/uc?export=download&id={$fileId}";
        
        try {
            $response = Http::withoutVerifying()
                ->timeout(5)
                ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                ->get($url);
            
            if ($response->successful() && strpos($response->header('Content-Type'), 'image') !== false) {
                $body = $response->body();
                $type = $response->header('Content-Type');
                
                // Cache for 7 days
                \Cache::put($cacheKey, ['body' => $body, 'type' => $type], 604800);
                
                return response($body)
                    ->header('Content-Type', $type)
                    ->header('Cache-Control', 'public, max-age=31536000');
            }
        } catch (\Exception $e) {
            \Log::error("Image proxy error for {$fileId}: " . $e->getMessage());
        }
        
        return redirect('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400');
    }
}
