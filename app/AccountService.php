<?php

namespace App;
use Storage;

class AccountService
{
	private $id;
	private $balance;
	public $filename = "temp.json";

	public function __construct()
	{
		if(Storage::disk('local')->exists($this->filename)) {
			$data = json_decode(Storage::disk('local')->get($this->filename), true);

			if(isset($data['id'])) $this->id = $data['id'];
			if(isset($data['balance'])) $this->balance = $data['balance'];
		}
	}

	public function reset()
	{
		$this->id = null;
		$this->balance = null;
		Storage::disk('local')->put($this->filename, "");
	}

	public function getBalance($account_id)
	{
		if($this->id == $account_id)
		{
			return $this->balance;
		} else {
			return null;
		}
	}
}


?>