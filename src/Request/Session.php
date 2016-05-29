<?php
namespace TRW\Request;

/**
* セッションを制御するクラス
*
*
*/
class Session {

/**
* セッションが開始されたかのフラグ.
*
* @var boolean 開始されていればture
*/
	private $started;

/**
* 任意のセッションデータを取得する.
*
* @param string $key 取得したいデータのキー
* @return mixid キーがあればその値を返す、なければfalse
*/
	public function read($key){
		if(!$this->started()){
			$this->start();
		}

		if($this->check($key)){
			return $_SESSION[$key];
		}
		
		return false;
	}

/**
* データをセッションに書き込む.
*
*
* @param string $key データを取得する際のキー
* @param mixid $value 書き込みたいデータ
*/
	public function write($key, $value = null){
		if(!$this->started()){
			$this->start();
		}
		
		$_SESSION[$key] = $value;
	}

/**
* セッションデータのキーの有無を確認する.
*
* @param string $key
* @return キーがあればtrue なければfalse
*/
	public function check($key){
		if(!$this->started()){
			$this->start();
		}
		if(array_key_exists($key, $_SESSION)){
			return true;
		}
		return false;
	}
	
/**
* 任意のセッションデータを消去する.
* 
* @param string $key 消去したいデータのキー 
* @return キーがあればture、なければfalse
*/
	public function delete($key){
		if($this->check($key)){
			$this->write($key, null);
			return true;
		}
		return false;
	}

/**
* セッションを開始する.
*
* @return boolean 開始に成功すればture
* @throws \Exception　開始に失敗した時
*/
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

/**
* セッションが開始しているか確認する.
*
* @return  開始していればture、開始していなければfalse
*/
	public function started(){
		return $this->started || session_status() === \PHP_SESSION_ACTIVE;
	}

/**
* セッションidを返す.
*
* @return string セッションid
*/
	public function id(){
		session_id();
	}

}
