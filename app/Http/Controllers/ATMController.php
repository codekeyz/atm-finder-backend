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
}
