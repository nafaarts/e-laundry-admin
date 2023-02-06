<?php

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserOrderController;
use App\Models\Laundry;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::resource('/address', AddressController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('/order', UserOrderController::class)->except(['create', 'edit', 'destroy']);
    Route::post('/edit-profile', [AuthController::class, 'updateProfile']);
    Route::post('/edit-profile-photo', [AuthController::class, 'updatePhoto']);
});

Route::get('/get-nearest-area', function () {
    $swLat = request('swLat') or abort(404);
    $swLng = request('swLng') or abort(404);
    $neLat = request('neLat') or abort(404);
    $neLng = request('neLng') or abort(404);

    $laundry = Laundry::whereBetween('lat', [$swLat, $neLat])
        ->whereBetween('long', [$swLng, $neLng])
        ->get();

    return $laundry;
});

Route::get('/notification/{id}', function ($id) {
    return Order::where([
        'is_viewed' => false,
        'laundry_id' => $id
    ])->count();
});

Route::get('/laundry/{id}', function ($id) {
    $laundry = Laundry::with('services', 'user')->findOrFail($id);
    $lat = request('lat');
    $lng = request('lng');

    if ($lat && $lng) {
        $laundry->distance = (float) number_format(6371 *
            acos(
                cos(deg2rad($lat))
                    * cos(deg2rad($laundry->lat))
                    * cos(deg2rad($laundry->long) - deg2rad($lng))
                    + sin(deg2rad($lat))
                    * sin(deg2rad($laundry->lat))
            ), 2);
    }

    $laundry->image = asset('img/laundry') . '/' . $laundry->image;
    $laundry->rate =  $laundry->getRating();
    $laundry->services = $laundry->services->map(function ($item) use ($laundry) {
        $item->icon = asset('img/icon') . '/' . $item->icon;
        $item->orders = $laundry->orders()->whereHas('details', function ($query) use ($item) {
            return $query->where('service_id', '=', $item->id);
        })->count();
        return $item;
    });

    $laundry->user->profile_picture = asset('img/user') . '/' . $laundry->user->profile_picture;

    return collect($laundry)->except(['user_id', 'lat', 'long', 'created_at', 'updated_at']);
});

Route::get('/laundry', function () {
    $lat = request('lat') or abort(404);
    $lng = request('lng') or abort(404);

    $laundry = Laundry::filter()->get();

    $laundry = $laundry->map(function ($item) use ($lat, $lng) {
        $distance = (float) number_format(6371 *
            acos(
                cos(deg2rad($lat))
                    * cos(deg2rad($item->lat))
                    * cos(deg2rad($item->long) - deg2rad($lng))
                    + sin(deg2rad($lat))
                    * sin(deg2rad($item->lat))
            ), 2);

        return [
            ...collect($item)->except(['user_id', 'lat', 'long', 'created_at', 'updated_at']),
            'distance' => $distance,
            'image' => asset('img/laundry') . '/' . $item->image,
            'has_pickup' => $item->has_pickup,
            'rate' => $item->getRating(),
            'cheapest_price' => $item->services()->orderBy('price', 'ASC')->first()->price ?? 0
        ];
    });

    $laundry = collect($laundry)->where('cheapest_price', '!=', 0);
    $filter = request('filter');
    switch ($filter) {
        case 'cheapest':
            $result = $laundry->sortBy('cheapest_price')->values()->all();
            break;
        case 'top-rated':
            $result = $laundry->where('rate', '>=', 4)->sortByDesc('rate')->values()->all();
            break;
        case 'pickup':
            $result = $laundry->where('has_pickup', true)->values()->all();
            break;
        default:
            $result = $laundry->sortBy('distance')->values()->all();
            break;
    }
    $pagination = new PaginationHelper(request());
    return $pagination->paginate(collect($result), 10)->withQueryString();
});
