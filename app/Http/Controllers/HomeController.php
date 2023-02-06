<?php

namespace App\Http\Controllers;

use App\Models\Laundry;
use App\Models\LaundryService;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (request()->user()->can('admin')) {
            $data['users'] = User::count();
            $data['laundry'] = Laundry::count();
            $data['services'] = LaundryService::count();
            $data['orders'] = Order::count();

            return view('home', compact('data'));
        }

        $data['costumer'] = Order::where('laundry_id', auth()->user()?->laundry->id)->groupBy('user_id')->count();
        $data['services'] = auth()->user()->laundry->services()->count() ?? 0;
        $data['orders'] = Order::where('laundry_id', auth()->user()?->laundry->id)->count();
        $data['amount'] = Order::where('laundry_id', auth()->user()?->laundry->id)->get()->reduce(function ($total, $item) {
            return $total += $item->details->reduce(function ($total, $item) {
                return $total += $item->price;
            });
        }, 0);

        return view('user-laundry.dashboard', compact('data'));
    }

    public function detail()
    {
        $laundry = auth()->user()->laundry;

        return view('user-laundry.detail', compact('laundry'));
    }
}
