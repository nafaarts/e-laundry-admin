<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LaundryService;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth('api')->user();
        $orders = Order::where('user_id', $user->id)->latest()->get();

        $result = collect($orders)->map(function ($item) {
            return [
                "id" => $item->id,
                "status" => $item->status,
                "laundry" => $item->laundry->name,
                "weight" => $item->getTotalWeight(),
                "price" => $item->getTotalPrice(),
                "is_paid" => $item->is_paid,
                "date_order" => $item->created_at->format('d F Y')
            ];
        });

        return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'laundry_id' => 'required',
            'with_pick_up'  => 'required',
            'detail_order' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'address_id' => Rule::requiredIf(request('with_pick_up') == true)
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Bad Request', 'validation' => $validator->messages()], 400);
        }

        try {
            $user = auth('api')->user();
            $order = Order::create([
                'user_id' => $user->id,
                'laundry_id' => request('laundry_id'),
                'with_pick_up' => request('with_pick_up'),
                'address_id' => request('address_id') ?? null,
                'lat' => request('lat'),
                'long' => request('long'),
            ]);

            foreach (request('detail_order') as $detail) {
                $service = LaundryService::findOrFail($detail['service_id']);
                $order->details()->create([
                    'service_id' => $service->id,
                    'weight' => $detail['weight'],
                    'price' => $service->price * $detail['weight']
                ]);
            }

            return response()->json([
                'messages' => 'new order successfully created!',
                'order' => $order,
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Server Error', 'messages' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth('api')->user();
        $order = Order::with('details')->where(['id' => $id, 'user_id' => $user->id])->first();

        return [
            "id" => $order->id,
            "date" => $order->created_at->format('d F Y'),
            "status" => $order->status,
            "laundry" => $order->laundry->name,
            "weight" => $order->getTotalWeight(),
            "price" => $order->getTotalPrice(),
            "is_paid" => $order->is_paid,
            "is_pickedup" => $order->is_pickedup,
            "with_pick_up" => $order->with_pick_up,
            "address" => $order->address->address ?? null,
            "review" => $order->review
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth('api')->user();
        $order = Order::where(['id' => $id, 'user_id' => $user->id])->first();
        switch (request('type')) {
            case 'STATUS':
                if (request()->has('status')) {
                    $order->update([
                        'status' => request('status')
                    ]);
                }
                break;

            case 'REVIEW':
                if ($order->review == null) {
                    $review = $order->review()->create([
                        'laundry_id' => $order->laundry_id,
                        'user_id' => $order->user_id,
                        'rating' => request('rating'),
                        'comments' => request('comment'),
                    ]);
                    return response()->json([
                        "message" => "Ulasan berhasil ditambahkan!",
                        "ulasan" => $review
                    ], 200);
                } else {
                    $review = $order->review()->update([
                        'rating' => request('rating'),
                        'comments' => request('comment'),
                    ]);
                    return response()->json([
                        "message" => "Ulasan berhasil diubah!",
                        "ulasan" => $review
                    ], 200);
                }
                break;

            default:
                return response()->json([
                    "message" => "no target update!"
                ], 400);
                break;
        }

        return $order;
    }
}
