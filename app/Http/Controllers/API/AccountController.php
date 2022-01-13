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
        Storage::disk('local')->put('temp.json', "");
        return response('OK', 200);
    }

    public function event(Request $request)
    {
        $myArr = array('type' => 'deposit', 'destination' => "100", 'amount' => 10);
        $myJSON = json_encode($myArr);
        Storage::disk('local')->put('temp.json', $myJSON);
        return Response::json([
            'destination' => [ "id"=>"100", "balance"=>10 ]
        ], 201);
    }

    public function balance(Request $request)
    {
        return response(200);
    }
}
