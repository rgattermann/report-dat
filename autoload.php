<?php
	function __autoload($class) {
		$filepath = "classes/{$class}.class.php";
		if(file_exists($filepath))
			require_once $filepath;
	}
?>