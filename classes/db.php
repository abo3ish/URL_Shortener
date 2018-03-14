<?php
class DB{
	private static $_instance = null;
	private $_pdo,
			$_query,
			$_count,
			$_results,
			$_error;

	private function __construct() {
		try{
			$this->_pdo = new PDO('mysql:host=localhost;dbname=shortener',"root","");
		} catch(Exception $e){
			die($e->getMessage());
		}
	}	
	public static function getInstance() {
		if(!isset(self::$_instance)) {
			self::$_instance = new DB();
		}
		return self::$_instance;
	}

	public function query($sql,$params = array()){
		if($this->_query = $this->_pdo->prepare($sql)){
			$x=1;
			if(count($params)){
				foreach($params as $param){
					$this->_query->bindValue($x,$param);
					$x++;
				}
			}
		}
		if($this->_query->execute()){
			$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
			$this->_count 	= $this->_query->rowCount();
			$this->_error = false;
		} else {
			$this->_error = true;
		}

		return $this;
	}
	public function action($action,$table,$where=array()){
		$field		 = $where[0]; 
		$operation 	 = $where[1]; 
		$value 		 = $where[2];
		$sql = "{$action} FROM {$table} WHERE {$field} {$operation} ?";
		if(!$this->query($sql,array($value))->error()){
			return $this;
		} 

	}
	public function select($table,$where=array()){
		return $this->action('select *',$table,$where);
	}
	public function insert($table,$fields=array()){
		if(count($fields)){
			$keys = array_keys($fields);
			$value = '?';
			for($i=1;$i<count($fields);$i++){
				$value .= ',?';
			}
			$sql = "INSERT INTO $table(`" . implode('`,`', $keys) .  "`) VALUES($value)";
			if(!$this->query($sql,$fields)->error()){
				return $this;
			}
		}
	}
	public function update($table,$fields=array(),$id){
		if(count($fields)){
			$keys = array_keys($fields);
			$set =  implode('=?,', $keys) . "=?";

		}
		// $sql = "UPDATE $table SET(username=?,password=?,salt=?) WHERE id=$id";
		$sql = "UPDATE $table SET {$set} WHERE id=  $id";
		echo $sql;
		if(!$this->query($sql,$fields)->error()){
				return true;
		}
		return false;


	}

	public function results(){
		return $this->_results;
	}	
	public function first(){
		return $this->results()[0];
	}
	public function count(){
		return $this->_count;
	}
	public function error(){
		return $this->_error;
	}
}