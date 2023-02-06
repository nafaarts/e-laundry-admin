<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::latest();
        $category = request('category') ?? null;
        switch ($category) {
            case 'proses':
                $orders = $orders->whereNotIn('status', ['SELESAI', 'DIBATALKAN'])->get();
                break;

            case 'selesai':
                $orders = $orders->where('status', 'SELESAI')->get();
                break;

            case 'dibatalkan':
                $orders = $orders->where('status', 'DIBATALKAN')->get();
                break;

            default:
                $orders = $orders->get();
                break;
        }

        return view('order.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $transaction)
    {
        return view('order.detail', compact('transaction'));
    }
}
