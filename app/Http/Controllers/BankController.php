<?php

namespace App\Http\Controllers;

use App\Http\Resources\ATMCollection;
use App\Http\Resources\ATMResource;
use App\Models\Bank;
use App\Search\BankSearch;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTAuth;

class BankController extends Controller
{
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function getOneOrAllBanks(Request $request)
    {
        $result = BankSearch::apply($request);
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
            'country' => 'required'
        ]);
        $payload = $request->all();
        $payload['password'] = Hash::make($payload['password']);
        $bank = Bank::create($payload);
        return response()->json($bank, 201);
    }

    public function update($id, Request $request)
    {
        $Bank = Bank::findOrFail($id);
        $Bank->update($request->all());

        return response()->json($Bank, 200);
    }

    public function delete($id){
        Bank::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);

        try {

            if (!$token = $this->jwt->attempt($request->only('email', 'password'))) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        } catch (ValidationException $exception) {

    }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        $this->jwt->invalidate($this->jwt->getToken());

        return response()->json(['message' => 'Successfully logged you out' ]);
    }

    public function refresh()
    {
        return $this->respondWithToken($this->jwt->refresh());
    }

    public function me() {
        return response()->json($this->jwt->user());
    }

    public function getManagers() {
        return response()->json($this->jwt->user()->managers);
    }

    public function getATMs() {
        return ATMResource::collection($this->jwt->user()->atms);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'type' => 'bearer',
            'expires_in' => $this->jwt->factory()->getTTL() * 60
        ]);
    }



}
