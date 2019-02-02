<?php

namespace App\Http\Controllers;

use App\Models\ATM;
use App\Search\ATMSearch;
use Illuminate\Http\Request;

class ATMController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getOneOrAllATMs(Request $request, ATM $atm)
    {
        $result = ATMSearch::apply($request);
        if ($result->count() == 1){
            $result = $result->first();
        }
        return response()->json($result);
    }

    public function create(Request $request)
    {
        $author = ATM::create($request->all());

        return response()->json($author, 201);
    }

    public function update($id, Request $request)
    {
        $atm = ATM::findOrFail($id);
        $atm->update($request->all());

        return response()->json($atm, 200);
    }

    public function delete($id)
    {
        ATM::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
