<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('vue');
});

Auth::routes();

/**
 * NOTE: Vueのルーティング対策で、これまで無いルーティングはすべてここにVueのSPAルートに集結
 */
// NOTE: default
Route::get('/{controller?}/{action?}/{any?}', function ($page) {
    if(!\Illuminate\Support\Facades\Auth::user()) {
        return redirect('/login');
    }
    return view('vue');
});
