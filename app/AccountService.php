<?php

namespace App;
use Storage;

class AccountService
{
	public $id;
	public $balance;
	public $filename = "temp.json";

	public function __construct()
	{
		if(Storage::disk('local')->exists($this->filename)) {
			$data = json_decode(Storage::disk('local')->get($this->filename), true);

			if(isset($data['id'])) $this->id = $data['id'];
			if(isset($data['balance'])) $this->balance = $data['balance'];
		}
	}
}


?>