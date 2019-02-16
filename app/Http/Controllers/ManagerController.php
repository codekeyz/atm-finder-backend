<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManagerResource;
use App\Models\Manager;
use App\Search\ManagerSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Dotenv\Exception\ValidationException;

class ManagerController extends Controller
{
    public function getManagers(Request $request){
        return ManagerResource::collection(ManagerSearch::apply($request));
    }

    public function me()
    {
        return new ManagerResource($this->guard()->user());
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);

        try {

            if (!$token = $this->guard()->attempt($request->only('email', 'password'))) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        } catch (ValidationException $exception) {
        }

        return $this->respondWithToken($token);

    }

    public function getManager($id){
        $manager = Manager::findOrFail($id);
        return new ManagerResource($manager);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:managers',
            'password' => 'required',
            'branch_id' => 'required'
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

    public function guard() {
        return Auth::guard('manager');
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'user' => $this->me(),
            'token' => $token,
            'type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }
}
