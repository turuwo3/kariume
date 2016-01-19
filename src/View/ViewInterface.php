<?php
namespace TRW\View;

interface ViewInterface {


	public function assign($name, $value);

	public function fetch($name);

	public function setViewVars($vars);

	public function render($file);

}
