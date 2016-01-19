<?php
namespace TRW\Request;

interface Request {

	public function has($key);

	public function data($key = null);

}
