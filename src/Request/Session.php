<?php
namespace TRW\Request;

class Session {

	private $started;

	public function read($key){
		if(!$this->started()){
			$this->start();
		}

		if($this->check($key)){
			return $_SESSION[$key];
		}
		
		return false;
	}

	public function write($key, $value = null){
		if(!$this->started()){
			$this->start();
		}
		
		$_SESSION[$key] = $value;
	}

	public function check($key){
		if(!$this->started()){
			$this->start();
		}
		if(array_key_exists($key, $_SESSION)){
			return true;
		}
		return false;
	}
	
	public function delete($key){
		if($this->check($key)){
			$this->write($key, null);
			return true;
		}
		return false;
	}

	public function start(){
		if($this->started){
			return true;
		}

		if(session_status() === \PHP_SESSION_ACTIVE){
			throw new Exception('session status error');
		}

		if(!session_start()){
			throw new Exception('session start errot');
		}

		return $this->started = true;
	}

	public function started(){
		return $this->started || session_status() === \PHP_SESSION_ACTIVE;
	}

	public function id(){
		session_id();
	}

}
