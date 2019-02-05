<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Search\BankSearch;
use Illuminate\Http\Request;

class BankController extends Controller
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

    public function getOneOrAllBanks(Request $request)
    {
        $result = BankSearch::apply($request);
        if ($result->count() == 1){
            $result = $result->first();
        }
        return response()->json($result);
    }

    public function create(Request $request)
    {
        $author = Bank::create($request->all());

        return response()->json($author, 201);
    }

    public function update($id, Request $request)
    {
        $Bank = Bank::findOrFail($id);
        $Bank->update($request->all());

        return response()->json($Bank, 200);
    }

    public function delete($id)
    {
        Bank::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
