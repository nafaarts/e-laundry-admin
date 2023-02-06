<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class LaundryOrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->laundry->orders()->latest();
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
        return view('user-laundry.order', compact('orders'));
    }

    public function detail(Order $order)
    {
        if (!$order->is_viewed) {
            $order->update([
                'is_viewed' => true
            ]);
        }
        return view('user-laundry.order-detail', compact('order'));
    }

    public function updateStatus(Order $order)
    {
        $type = request('type');
        switch ($type) {
            case 'STATUS':
                $order->update(['status' => request('status')]);
                if (request('weight') != $order->getTotalWeight()) {
                    $edit = $order->details()->first();
                    $edit->update(['weight' => request('weight'), 'price' => $edit->service->price * (float) request('weight')]);
                }
                break;

            case 'PAID':
                $order->update(['is_paid' => request('status')]);
                break;

            case 'PICKEDUP':
                $order->update(['is_pickedup' => request('status')]);
                break;

            default:
                abort(404);
                break;
        }

        return redirect()->route('laundry-orders.detail', $order)->with('success', 'Status berhasil di update!');
    }
}
