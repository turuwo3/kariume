<?php

namespace TRW\View;

use TRW\View\ViewBlock; 
use TRW\Exception\MissingTemplateException;

/**
* ファイルのレンダリングを行うクラス.
*
*
*
*/
class View {

/**
* ファイルの拡張子.
*
* @var string
*/
	private $extension = '.tmpl';

/**
* レイアウトファイルへのパス.
*
* pvar sring
*/
	private $layoutPath;

/*
* デフォルトのレイアウトファイル名.
*
* @var string
*/
	private $layoutFile = 'default';

/**
* ビューファイルへのパス.
*
* @var string
*/
	private $viewPath;

/**
* エレメントファイルへのパス.
*
* @var string
*/
	private $elementPath;

/**
* ファイルの出力のバッファを保持するクラス
*
* @var \TRW\View\ViewBlock
*/
	private $viewBlock;

/**
* ファイルへ展開する変数のリスト.
*
* @var array
*/
	private $viewVars = [];

	public function __construct($viewPath, $layoutPath, $elementPath, $viewVars = array()){
		$this->layoutPath = $layoutPath;
		$this->viewPath = $viewPath;
		$this->elementPath = $elementPath;
		$this->viewVars = $viewVars;
		$this->viewBlock = new ViewBlock();
	}


/**
* レイアウトファイルを取得する.
*
* @param string $fileName　取得したいレイアウトファイル名
* @return string　パス付のファイル名.
* @throws \TRW\Exception\MissingTemplateException ファイルが見つからない時
*/
	public function getLayoutFile($fileName){
		$file = $this->isFile($this->layoutPath, $fileName);	
		if($file === false){
			throw new MissingTemplateException('layout not found ' . $fileName);
		}
		return $file;
	}

/**
* ビューファイルを取得する.
*
* @param string $fileName　取得したいビューファイル名
* @return string　パス付のファイル名.
* @throws \TRW\Exception\MissingTemplateException ファイルが見つからない時
*/
	public function getViewFile($fileName){
		$file = $this->isFile($this->viewPath, $fileName);	
		if($file === false){
			throw new MissingTemplateException('view not found ' . $fileName);
		}
		return $file;
	}


/**
* エレメントファイルを取得する.
*
* @param string $fileName　取得したいエレメントファイル名
* @return string　パス付のファイル名.
* @throws \TRW\Exception\MissingTemplateException ファイルが見つからない時
*/
	public function getElementFile($fileName){
		$file = $this->isFile($this->elementPath, $fileName);	
		if($file === false){
			throw new MissingTemplateException('element not found ' . $fileName);
		}
		return $file;
	}

/**
* ファイルの有無を確認する.
*
* @param string $path ファイルパス
* @param string $file　ファイル名
* @return string|boolean ファイルが見つかればパス付のファイル名
* 見つからなければfalse
*/
	protected function isFile($path, $file){
		$file = $path . '/' . $file . $this->extension;
		if(is_file($file)){
			return $file;
		}
		return false;
	}

/**
* ファイルに展開するための変数を保持する.
*
* @param mixid $vars 展開したい変数
* @return void
*/
	public function setViewVars($vars){
		if($vars === null){
			$vars = [];
		}
		$this->viewVars = array_merge($this->viewVars, $vars);
	}

/**
* エレメントファイルを読み込む.
*
* @param string $fileName　エレメントファイル名
* @return エレメントファイルのバッファ
*/
	public function element($fileName){
		$elementFile = $this->getElementFile($fileName);
		$elementContent = $this->deploymentVars($elementFile, $this->viewVars);

		return $elementContent;
	}
/**
* ファイルをレンダリングする.
*
* レイアウトファイル、ビューファイルを組み合わせてレンダリングする
* エレメントファイルはレイアウト、ビューファイルで"echo $this->element"されていない限り出力されない 
*
* @param string $file 出力したいファイル名
* @return ファイルのバッファ
*/
	public function render($file){
		$data = $this->viewVars;
		$viewFile = $this->getViewFile($file);

		$viewContent = $this->deploymentVars($viewFile, $data);
		$this->viewBlock->set('content', $viewContent);

		$layoutFile = $this->getLayoutFile($this->layoutFile);

		$layoutContent = $this->deploymentVars($layoutFile, $data);
		$this->viewBlock->set('content', $layoutContent);

		return $this->viewBlock->get('content');
	}

/**
* ViewBlockへのセッター 
*
*/
	public function assign($name, $value){
		$this->viewBlock->set($name, $value);
	}

/**
* ViewBlockへのゲッター
*
*
*/
	public function fetch($name){
		return $this->viewBlock->get($name);
	}

/**
* 変数を展開したファイルのバッファを返す.
*
* @return ファイルのバッファ
*/
	private function deploymentVars($file, $vars = array()){
			extract($vars);
			ob_start();
			require $file;
			return ob_get_clean();
	}

}
