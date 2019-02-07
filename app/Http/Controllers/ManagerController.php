<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use App\Search\ManagerSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTAuth;

class ManagerController extends Controller
{
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function getOneOrAllManagers(Request $request)
    {
        $result = ManagerSearch::apply($request);
        if ($result->count() == 1) {
            $result = $result->first();
        }
        return response()->json($result);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255|unique:banks',
            'email' => 'required|email|max:255|unique:banks',
            'password' => 'required',
            'bank_id' => 'required'
        ]);
        $payload = $request->all();
        $payload['password'] = Hash::make($payload['password']);
        $manager = Manager::create($payload);
        return response()->json($manager, 201);
    }

    public function update($id, Request $request)
    {
        $manager = Manager::findOrFail($id);
        $manager->update($request->all());

        return response()->json($manager, 200);
    }

    public function delete($id)
    {
        Manager::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }

    public function me() {
        return response()->json($this->jwt->user());
    }

}
