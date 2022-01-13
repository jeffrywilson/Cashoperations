<?php

namespace App;
use Storage;

class AccountService
{
	private $account;
	public $filename = "temp.json";

	public function __construct()
	{
		if(Storage::disk('local')->exists($this->filename)) {
			$this->account = json_decode(Storage::disk('local')->get($this->filename), true);
		}
	}

	public function reset()
	{
		$this->account = null;
		Storage::disk('local')->put($this->filename, "");
	}

	public function getBalance($account_id)
	{
		if($this->account[$account_id]){
			return $this->account[$account_id]['balance'];
		} else {
			return null;
		}
	}

	public function handleEvent($request)
	{
		$eventType = $request->post('type');
		$destination = $request->post('destination');
		$origin = $request->post('origin');
		$amount = (int)$request->post('amount');
		$response['statusCode'] = 201;
		$response['data'] = null;

		switch ($eventType) {
			case 'deposit':
				if(isset($this->account[$destination])) {
					$this->account[$destination]['balance'] += $amount;
				} else {
					$this->account[$destination] = array("balance" => $amount);
				}
				$response['data'] = array("destination" => array("id" => $destination, "balance" => $this->account[$destination]['balance']));
                break;
			case 'withdraw':
				if(isset($this->account[$origin])) {
					$this->account[$origin]['balance'] -= $amount;
					$response['data'] = array("origin" => array("id" => $origin, "balance" => $this->account[$origin]['balance']));
				} else {
					$response['data'] = 0;
					$response['statusCode'] = 404;
				}
                break;
			case 'transfer':
				if(isset($this->account[$origin])) {
					$this->account[$origin]['balance'] -= $amount;
					if(isset($this->account[$destination])) {
						$this->account[$destination]['balance'] += $amount;
					} else {
						$this->account[$destination] = array("balance" => $amount);
					}
					
					$response['data'] = array(
						"origin" => array(
							"id" => $origin, "balance" => $this->account[$origin]['balance']
						), 
						"destination" => array(
							"id" => $destination, "balance" => $this->account[$destination]['balance']
						)
					);
				} else {
					$response['data'] = 0;
					$response['statusCode'] = 404;
				}
				
                break;
            default:
				break;

		}
		
        $myJSON = json_encode($this->account);
		Storage::disk('local')->put($this->filename, $myJSON);
		return $response;
	}
}
