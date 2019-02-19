<?php

namespace App\Http\Controllers;

use App\Http\Resources\ATMResource;
use App\Http\Resources\ManagerResource;
use App\Models\ATM;
use App\Models\Manager;
use App\Search\ATMSearch;
use App\Search\ManagerSearch;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
    public function getManagers(Request $request)
    {
        return ManagerResource::collection(ManagerSearch::apply($request));
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);

        try {

            if (!$token = $this->guard()->attempt($request->only('email', 'password'))) {
                return $this->sendErrorMessage(401, false, 'Unauthorized Access');
            }

        } catch (ValidationException $exception) {

        }

        return $this->respondWithToken($token);

    }

    public function guard()
    {
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

    public function me()
    {
        return new ManagerResource($this->guard()->user());
    }

    public function logout()
    {
        $this->guard()->invalidate($this->guard()->getToken());

        return response()->json(['message' => 'Successfully logged you out']);
    }

    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }


    public function update(Request $request)
    {
        $id = $this->guard()->user()->id;
        $manager = Manager::findOrFail($id);
        if (!$manager) {
            return $this->sendErrorMessage(404, false, 'Requested Resource not available.');
        }
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'name' => 'string',
        ]);
        $update = $request->only(['name', 'email']);
        $manager->update($update);
        return new ManagerResource($manager);

    }


    public function getMyATMS(Request $request)
    {
        $request['branch_id'] = $this->guard()->user()->branch->id;
        return ATMResource::collection(ATMSearch::apply($request));
    }

    public function updateATM($id, Request $request)
    {
        $this->validate($request, [
            'status' => 'required|numeric|between:-1,1'
        ]);
        $atm = (new ATM)->newQuery();
        $result = $atm
            ->where('branch_id', $this->guard()->user()->branch->id)
            ->where('id', $id)
            ->first();
        if ($result) {
            $update = $request->only(['status']);
            $result->update($update);
            return new ATMResource($result);
        } else {
            return $this->sendErrorMessage(404, false, 'Requested Resource not available.');
        }
    }
}
