<?php
	function __autoload($class) {
		$filepath = "classes/{$class}.class.php";
		if(file_exists($filepath))
			require_once $filepath;
		else
			throw new Exception("Unable to load {$class_name}.");
	}
?>