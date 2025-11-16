<?php

use Illuminate\Support\Facades\Route;

// Google OAuth callback for development only
Route::get('/callback', function () {
    $code = request('code');
    $error = request('error');
    
    if ($error) {
        return response("Error: {$error}", 400);
    }
    
    if ($code) {
        return response("
            <h2>✅ Authorization Successful!</h2>
            <p><strong>Your authorization code:</strong></p>
            <code style='background: #f5f5f5; padding: 10px; display: block; margin: 10px 0;'>{$code}</code>
            <p>Copy this code and paste it into your terminal.</p>
            <p>You can close this window.</p>
        ");
    }
    
    return response('No authorization code received', 400);
});

Route::get('/google-drive-extractor', function () {
    return view('google-drive-extractor');
});