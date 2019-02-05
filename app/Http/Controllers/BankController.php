<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Search\BankSearch;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class BankController extends Controller
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function getOneOrAllBanks(Request $request, Bank $Bank)
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

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required',
        ]);

        try {

            if (! $token = $this->jwt->attempt($request->only('email', 'password'))) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 500);

        }

        return response()->json(compact('token'));
    }
}
