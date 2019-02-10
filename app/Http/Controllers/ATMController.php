<?php

namespace App\Http\Controllers;

use App\Http\Resources\ATMResource;
use App\Models\ATM;
use App\Search\ATMSearch;
use Illuminate\Http\Request;

class ATMController extends Controller
{

    public function __construct()
    {
        //
    }

    public function getAllATMs(Request $request)
    {
        return ATMResource::collection(ATMSearch::apply($request));
    }

    public function getATM($id) {
        $atm = ATM::findOrFail($id);
        return new ATMResource($atm);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'city' => 'required|string',
            'status' => 'required|numeric',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric'
        ]);
        $payload = $request->all();
        $payload['bank_id'] = $request->user()->id;
        $atm = ATM::create($payload);
        return new ATMResource($atm);
    }

    public function update($id, Request $request)
    {
        $atm = ATM::findOrFail($id);
        $update = $request->only(['name', 'status', 'lat', 'lng', 'city']);
        $atm->update($update);
        return new ATMResource($atm);
    }

    public function delete($id)
    {
        ATM::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
