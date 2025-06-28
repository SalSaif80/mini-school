<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/user', function (Request $request) {
    return 'hello';
});

Route::post('/send', function () {
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('LOG_API_TOKEN'),
        'Content-Type' => 'application/json',
    ])->post('http://192.168.10.59/api/admin/system-status');

    return response()->json([
        'status' => $response->successful() ? 'success' : 'error',
        'status_code' => $response->status(),
        'data' => $response->json(),
        'headers' => $response->headers(),
    ]);
    return $response->body();
});