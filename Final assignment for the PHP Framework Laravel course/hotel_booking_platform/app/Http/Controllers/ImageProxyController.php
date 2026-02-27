<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ImageProxyController extends Controller
{
    public function proxy($fileId)
    {
        $url = "https://drive.google.com/uc?export=view&id={$fileId}";
        
        try {
            $response = Http::withOptions(['verify' => false])->timeout(5)->get($url);
            
            if ($response->successful()) {
                return response($response->body())
                    ->header('Content-Type', $response->header('Content-Type') ?? 'image/jpeg')
                    ->header('Cache-Control', 'public, max-age=86400');
            }
        } catch (\Exception $e) {
            // Return 1x1 transparent pixel on error
        }
        
        $pixel = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
        return response($pixel)->header('Content-Type', 'image/png');
    }
}
