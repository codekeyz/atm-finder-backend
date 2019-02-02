<?php

namespace App\Http\Controllers;

use App\Models\ATM;
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

    public function getAllATMs()
    {
        return response()->json(ATM::all());
    }

    public function getOneATM($id)
    {
        return response()->json(ATM::find($id));
    }

    public function create(Request $request)
    {
        $author = ATM::create($request->all());

        return response()->json($author, 201);
    }

    public function update($id, Request $request)
    {
        $author = ATM::findOrFail($id);
        $author->update($request->all());

        return response()->json($author, 200);
    }

    public function delete($id)
    {
        ATM::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
