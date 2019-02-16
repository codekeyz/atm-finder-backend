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
            'lng' => 'required|numeric',
            'branch_id' => 'required|numeric|exists:branches,id'
        ]);
        $payload = $request->all();
        $payload['bank_id'] = $request->user()->id;
        $atm = ATM::create($payload);
        return new ATMResource($atm);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'max:255',
            'city' => 'string',
            'status' => 'numeric',
            'lat' => 'numeric',
            'lng' => 'numeric',
            'branch_id' => 'numeric'
        ]);
        $atm = ATM::findOrFail($id);
        $update = $request->only(['name', 'status', 'lat', 'lng', 'city', 'branch_id']);
        $atm->update($update);
        return new ATMResource($atm);
    }

    public function delete($id, Request $request)
    {
        $atm = (new ATM)->newQuery();
        $result = $atm
            ->where('bank_id', $request->user()->id)
            ->where('id', $id)
            ->first();
        if (!$result) {
            return $this->sendErrorMessage(404, false, 'Requested Resource not available.');
        }
        $result->delete();
        return $this->sendErrorMessage(200, true, 'Action completed successfully');
    }
}
