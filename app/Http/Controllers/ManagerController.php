<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManagerResource;
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

    public function getManagers(Request $request)
    {
        return ManagerResource::collection(ManagerSearch::apply($request));
    }

    public function getManager($id) {
        $manager = Manager::findOrFail($id);
        return new ManagerResource($manager);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:managers',
            'password' => 'required'
        ]);
        $payload = $request->all();
        $payload['bank_id'] = $request->user()->id;
        $payload['password'] = Hash::make($payload['password']);
        $manager = Manager::create($payload);
        return new ManagerResource($manager);
    }

    public function update($id, Request $request)
    {
        $manager = Manager::findOrFail($id);
        $update = $request->only(['name', 'email']);
        $manager->update($update);
        return new ManagerResource($manager);
    }

    public function delete($id)
    {
        Manager::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }



}
