<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/health/db', function () {
    try {
        DB::select('select 1 as result');

        return response()->json([
            'status' => 'ok',
            'database' => config('database.default'),
        ]);
    } catch (\Throwable $exception) {
        Log::error('Database health check failed', [
            'connection' => config('database.default'),
            'message' => $exception->getMessage(),
        ]);

        return response()->json([
            'status' => 'error',
            'message' => $exception->getMessage(),
            'connection' => config('database.default'),
        ], 500);
    }
});
