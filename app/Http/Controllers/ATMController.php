<?php

namespace App\Http\Controllers;

use App\Http\Resources\ATMResource;
use App\Models\ATM;
use App\Search\ATMSearch;
use Illuminate\Http\Request;

class ATMController extends Controller
{

    public function getAllATMs(Request $request)
    {
        return ATMResource::collection(ATMSearch::apply($request));
    }

    public function getATM($id) {
        $atm = ATM::findOrFail($id);
        if (!$atm){
            return $this->sendErrorMessage(404, false, 'Requested Resource not available.');
        }
        return new ATMResource($atm);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'max:255',
            'city' => 'string',
            'status' => 'numeric|between:-1,1',
            'lat' => 'numeric',
            'lng' => 'numeric',
            'branch_id' => 'numeric'
        ]);
        $atm = ATM::findOrFail($id);
        if (!$atm){
            return $this->sendErrorMessage(404, false, 'Requested Resource not available.');
        }
        $update = $request->only(['name', 'status', 'lat', 'lng', 'city', 'branch_id']);
        $atm->update($update);
        return new ATMResource($atm);
    }

}
