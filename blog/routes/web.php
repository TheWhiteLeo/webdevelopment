<?php

use App\Http\Controllers\Api\Blog\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestTestController;
use App\Http\Controllers\DiggingDeeperController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::apiResource('rest', RestTestController::class)->names('restTest');

Route::group([ 'namespace' => 'App\Http\Controllers\Api\Blog', 'prefix' => 'blog'], function () {
    Route::apiResource('posts', PostController::class)->names('blog.posts');
});

Route::group(['prefix' => 'digging_deeper'], function () {

    Route::get('collections', [DiggingDeeperController::class, 'collections'])
        ->name('digging_deeper.collections');

    Route::get('process-video', 'App\Http\Controllers\DiggingDeeperController@processVideo')
        ->name('digging_deeper.processVideo');

    Route::get('prepare-catalog', 'App\Http\Controllers\DiggingDeeperController@prepareCatalog')
        ->name('digging_deeper.prepareCatalog');


});
