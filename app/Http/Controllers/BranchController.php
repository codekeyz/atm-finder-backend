<?php

namespace App\Http\Controllers;

use App\Http\Resources\BranchResource;
use App\Models\Branch;
use App\Search\BranchSearch;
use Illuminate\Http\Request;

class BranchController extends Controller
{


    public function getAllBranches(Request $request) {
        return BranchResource::collection(BranchSearch::apply($request));
    }

    public function create(Request $request) {

        $this->validate($request, [
            'name' => 'required|string|max:255|unique:branches',
            'city' => 'required|string',
            'town' => 'string'
        ]);
        $payload = $request->all();
        $payload['bank_id'] = $request->user()->id;
        $branch = Branch::create($payload);
        return new BranchResource($branch);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'string|max:255|unique:branches',
            'city' => 'string',
            'town' => 'string'
        ]);
        $branch = Branch::findOrFail($id);
        $update = $request->only(['name', 'city', 'town']);
        $branch->update($update);
        return new BranchResource($branch);
    }


    public function getBranch($id) {
        $branch = Branch::findOrFail($id);
        return new BranchResource($branch);
    }

    public function delete($id) {
        Branch::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
