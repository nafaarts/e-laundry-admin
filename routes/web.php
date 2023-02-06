<?php

use App\Http\Controllers\LaundryOrderController;
use App\Models\Laundry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Auth::routes([
    'verify' => false,
    'reset' => false,
    'register' => false,
]);

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/detail', [App\Http\Controllers\HomeController::class, 'detail'])->name('detail');

Route::middleware('auth')->group(function () {
    Route::resource('users', \App\Http\Controllers\UserController::class)->except('show')->middleware('can:admin');
    Route::resource('laundry', \App\Http\Controllers\LaundryController::class);
    Route::resource('service', \App\Http\Controllers\LaundryServiceController::class)->except('index', 'show');
    Route::resource('transactions', \App\Http\Controllers\OrderController::class)->names('orders')->middleware('can:admin');
    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    Route::get('orders', [LaundryOrderController::class, 'index'])->name('laundry-orders');
    Route::get('orders/{order}/detail', [LaundryOrderController::class, 'detail'])->name('laundry-orders.detail');
    Route::get('orders/{order}/status', [LaundryOrderController::class, 'updateStatus'])->name('laundry-orders.update-status');
});

Route::get('explore', function () {
    return view('explore');
});

Route::get('open-explore', function () {
    $id = request('id') or abort(404);
    $laundry = Laundry::findOrFail($id);
    echo "redirect to mobile...";
    echo "<br>";
    echo "<small>$laundry->name</small>";
})->name('open.explore');

Route::get('/sample', function () {
    echo config('app.env');
});
