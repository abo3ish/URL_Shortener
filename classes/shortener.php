<?php
require_once "db.php";

class Shortener{
	protected $db;
	public function __construct(){
		$this->db = DB::getInstance();
	}
	public function makeCode(){
		$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$randChars = "";
		for($i = 0;$i<5;$i++){
			$rand = random_int(0, strlen($chars)-1);
			$randChars .= $chars[$rand];
		}
		return $randChars;
	}

	public function makeURL($url){
		if(!filter_var($url,FILTER_VALIDATE_URL)){
			return "";
		}
		$exits = $this->db->select("links",array("url","=",$url));
		if($exits->count()){
			return $exits->first()->code;
		} else {
			$randChars = $this->makeCode();
			$this->db->insert("links",array("url" => $url,"code" => $randChars,"date" =>date('Y:m:d H:i:s')));
			return $randChars;
		}	

	}
	public function getURL($code){
		$code = htmlentities($code);
		$code =  $this->db->select("links",array("code","=",$code));
		if($code->count()){
			return $code->first()->url;
		} else {
			return "";
		}
	}
}