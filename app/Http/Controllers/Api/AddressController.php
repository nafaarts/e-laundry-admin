<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth('api')->user();
        return $user->address;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Bad Request', 'validation' => $validator->messages()], 400);
        }

        $user = auth('api')->user();
        $new_address = $user->address()->create([
            'address' => request('address')
        ]);

        return response()->json($new_address, 200);
    }

    public function update($id)
    {
        $user = auth('api')->user();
        $address = $user->address()->findOrFail($id);

        $updated = $address->update([
            'address' => request('address')
        ]);

        return response()->json([
            'messages' => 'Successfully updated!',
            'data' => $updated
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth('api')->user();
        $address = $user->address()->findOrFail($id);
        $address->delete();

        return response()->json([
            'messages' => 'Successfully deleted!',
            'data' => $address
        ], 200);
    }
}
