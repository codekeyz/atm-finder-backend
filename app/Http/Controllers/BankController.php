<?php

namespace App\Http\Controllers;

use App\Http\Resources\ATMResource;
use App\Http\Resources\BankResource;
use App\Http\Resources\BranchResource;
use App\Http\Resources\ManagerResource;
use App\Models\ATM;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\Manager;
use App\Search\ATMSearch;
use App\Search\BankSearch;
use App\Search\BranchSearch;
use App\Search\ManagerSearch;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class BankController extends Controller
{

    public function __construct()
    {
//        $this->middleware('auth:api', ['except' => ['login', 'create']]);
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
            'slogan' => 'string',
            'password' => 'required',
            'country' => 'required|string',
            'town' => 'required|string'
        ]);
        $payload = $request->all();
        $payload['password'] = Hash::make($payload['password']);
        $payload['desc'] = $payload['slogan'];
        $bank = Bank::create($payload);
        return new BankResource($bank);
    }

    public function update(Request $request)
    {
        $id = $request->user()->id;
        $bank = Bank::findOrFail($id);
        if (!$bank) {
            return $this->sendErrorMessage(404, false, 'Requested Resource not available');
        }
        $bank->update($request->all());
        return new BankResource($bank);
    }

    public function delete(Request $request)
    {
        $id = $request->user()->id;
        $bank = Bank::findOrFail($id);
        if (!$bank) {
            return $this->sendErrorMessage(404, false, 'Requested Resource not available');
        }
        $bank->delete();
        return $this->sendErrorMessage(200, true, 'Action completed successfully');
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
        return Auth::guard('bank');
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
        return new BankResource($this->guard()->user());
    }

    public function logout()
    {
        $this->guard()->invalidate($this->guard()->getToken());

        return response()->json(['message' => 'Successfully logged you out']);
    }

    public function refresh()
    {
        try {

            $newtoken = $this->guard()->refresh();

        } catch (TokenExpiredException $e) {
            return $this->sendErrorMessage(404, false, 'token_expired');
        }catch (TokenInvalidException $e) {
            return $this->sendErrorMessage(404, false, 'token_invalid');
        }catch (JWTException $e) {
            return $this->sendErrorMessage(404, false, 'token_absent');
        }

        return $this->respondWithToken($newtoken);
    }


    /*************************************************************** ATM Functions *********************************/

    public function getATMs(Request $request)
    {
        $request['bank_id'] = $request->user()->id;
        return ATMResource::collection(ATMSearch::apply($request));
    }

    public function getATM($id, Request $request)
    {
        $atm = (new ATM)->newQuery();
        $result = $atm
            ->where('bank_id', $request->user()->id)
            ->where('id', $id)
            ->first();
        if (!$result) {
            return $this->sendErrorMessage(404, false, 'Requested Resource not available.');
        }
        return new ATMResource($result);
    }

    public function createATM(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'city' => 'required|string',
            'status' => 'required|numeric|between:-1,1',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'branch_id' => 'required|numeric|exists:branches,id'
        ]);
        $payload = $request->all();
        $payload['bank_id'] = $request->user()->id;
        $atm = ATM::create($payload);
        return new ATMResource($atm);
    }

    public function updateATM($id, Request $request)
    {
        $atm = (new ATM)->newQuery();
        $result = $atm
            ->where('bank_id', $request->user()->id)
            ->where('id', $id)
            ->first();
        if (!$result) {
            return $this->sendErrorMessage(404, false, 'Requested Resource not available.');
        }

        $this->validate($request, [
            'name' => 'max:255|unique:atms,name,'.$result->id,
            'city' => 'string',
            'status' => 'numeric|between:-1,1',
            'lat' => 'numeric',
            'lng' => 'numeric',
            'branch_id' => 'numeric'
        ]);
        $update = $request->only(['name', 'status', 'lat', 'lng', 'city', 'branch_id']);
        $result->update($update);
        return new ATMResource($result);
    }

    public function deleteATM($id, Request $request)
    {
        $atm = (new ATM)->newQuery();
        $result = $atm
            ->where('bank_id', $request->user()->id)
            ->where('id', $id)
            ->first();
        if (!$result) {
            return $this->sendErrorMessage(404, false, 'Requested Resource not available.');
        }
        $result->delete();
        return $this->sendErrorMessage(200, true, 'Action completed successfully');
    }

    /*************************************************************** Manager Functions *********************************/

    public function getManagers(Request $request)
    {
        // Return managers for a bank
        $request['bank_id'] = $request->user()->id;
        return ManagerResource::collection(ManagerSearch::apply($request));
    }

    public function getManager($id, Request $request)
    {
        $manager = (new Manager)->newQuery();
        $result = $manager
            ->where('bank_id', $request->user()->id)
            ->where('id', $id)
            ->first();
        if (!$result) {
            return $this->sendErrorMessage(404, false, 'Requested Resource not available.');
        }
        return new ManagerResource($result);
    }

    public function createManager(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:managers',
            'password' => 'required',
            'branch_id' => 'required|numeric|exists:branches,id'
        ]);
        $payload = $request->all();
        $payload['bank_id'] = $request->user()->id;
        $payload['password'] = Hash::make($payload['password']);
        $manager = Manager::create($payload);
        return new ManagerResource($manager);
    }

    public function updateManager($id, Request $request)
    {
        $manager = (new Manager)->newQuery();
        $result = $manager
            ->where('bank_id', $request->user()->id)
            ->where('id', $id)
            ->first();
        if (!$result) {
            return $this->sendErrorMessage(404, false, 'Requested Resource not available.');
        }
        $this->validate($request, [
            'email' => 'required|email|unique:managers,email,'.$result->id,
            'name' => 'string',
        ]);
        $update = $request->only(['name', 'email']);
        $result->update($update);
        return new ManagerResource($result);
    }

    public function deleteManager($id, Request $request)
    {
        $manager = (new Manager)->newQuery();
        $result = $manager
            ->where('bank_id', $request->user()->id)
            ->where('id', $id)
            ->first();
        if (!$result) {
            return $this->sendErrorMessage(404, false, 'Requested Resource not available.');
        }
        $result->delete();
        return $this->sendErrorMessage(200, true, 'Action completed successfully');
    }

    /************************************************* Branch Functions ****************************************/

    public function getBranches(Request $request)
    {
        $request['bank_id'] = $request->user()->id;
        return BranchResource::collection(BranchSearch::apply($request));
    }

    public function getBranch($id, Request $request)
    {
        $branch = (new Branch)->newQuery();
        $result = $branch
            ->where('bank_id', $request->user()->id)
            ->where('id', $id)
            ->first();
        if (!$result) {
            return $this->sendErrorMessage(404, false, 'Requested Resource not available.');
        }
        return new BranchResource($result);
    }

    public function createBranch(Request $request)
    {
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

    public function updateBranch($id, Request $request)
    {
        $branch = (new Branch)->newQuery();
        $result = $branch
            ->where('bank_id', $request->user()->id)
            ->where('id', $id)
            ->first();
        if (!$result) {
            return $this->sendErrorMessage(404, false, 'Requested Resource not available.');
        }

        $this->validate($request, [
            'name' => 'unique:branches,name,'.$result->id,
            'city' => 'string',
            'town' => 'string'
        ]);
        $update = $request->only(['name', 'city', 'town']);
        $result->update($update);
        return new BranchResource($result);
    }

    public function deleteBranch($id, Request $request)
    {
        $branch = (new Branch)->newQuery();
        $result = $branch
            ->where('bank_id', $request->user()->id)
            ->where('id', $id)
            ->first();
        if (!$result) {
            return $this->sendErrorMessage(404, false, 'Requested Resource not available.');
        }
        $result->delete();
        return $this->sendErrorMessage(200, true, 'Action completed successfully');
    }
}
