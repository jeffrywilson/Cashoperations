<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AccountService;
use Response;
use Storage;

class AccountController extends Controller
{
    public $accountService;

    public function __construct()
    {
        $this->accountService = new AccountService();
    }

    public function reset(Request $request)
    {
        $this->accountService->reset();
        return response('OK', 200);
    }

    public function event(Request $request)
    {
        $response = $this->accountService->handleEvent($request);
        return Response::json($response['data'], $response['statusCode']);
    }

    public function balance(Request $request)
    {
        $balance = $this->accountService->getBalance($request->get('account_id'));
        if($balance == null) {
            return response(0, 404);
        }
        return response($balance, 200);
    }
}
