<?php

use App\Http\Controllers\CourseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});



Route::apiResource('/courses', CourseController::class)->only(['index', 'show']);



Route::get('/filters', function () {

    return response()->json([
        'categories' => \App\Models\Category::all()->pluck('name'),
        'levels' =>  ['beginner', 'intermediate', 'advanced'],
        'prices' => ['paid', 'free'],
        'instructors' => \App\Models\User::where('role', 'admin')->get()->pluck('name'),
        'topics' => \App\Models\Tag::all()->pluck('name'),
    ]);
});
