<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

/**
 * Serve storage files via PHP's built-in dev server (php artisan serve).
 * When using Apache/WAMP the symlink handles this automatically,
 * but php artisan serve does not serve the public/storage symlink.
 */
Route::get('/storage/{path}', function (string $path) {
    // Files stored with Storage::disk('public') go to storage/app/public/
    $storagePath = storage_path('app/public/' . $path);

    if (!file_exists($storagePath)) {
        abort(404, 'File not found: ' . $storagePath);
    }

    $mimeType = mime_content_type($storagePath) ?: 'application/octet-stream';
    return response()->file($storagePath, [
        'Content-Type'                => $mimeType,
        'Access-Control-Allow-Origin' => '*',
    ]);
})->where('path', '.*');
