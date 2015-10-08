<?php
	require_once 'autoload.php';
	
	try {
		$searchboot = new Search();
		$searchboot->colectData();
	} catch (Exception $e) {
	    echo $e->getMessage().PHP_EOL;
	}
?>