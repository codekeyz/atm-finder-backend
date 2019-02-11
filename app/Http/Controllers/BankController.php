<?php

namespace App\Http\Controllers;

use App\Http\Resources\ATMCollection;
use App\Http\Resources\ATMResource;
use App\Http\Resources\BankResource;
use App\Models\Bank;
use App\Search\ATMSearch;
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
        return BankResource::collection(BankSearch::apply($request));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255|unique:banks',
            'email' => 'required|email|max:255|unique:banks',
            'password' => 'required',
            'country' => 'required|string',
            'town' => 'required|string'
        ]);
        $payload = $request->all();
        $payload['password'] = Hash::make($payload['password']);
        $bank = Bank::create($payload);
        return new BankResource($bank);
    }

    public function update(Request $request)
    {
        $id = $request->user()->id;
        $bank = Bank::findOrFail($id);
        $bank->update($request->all());

        return new BankResource($bank);
    }

    public function delete(Request $request){
        $id = $request->user()->id;
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
        return new BankResource($this->jwt->user());
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
