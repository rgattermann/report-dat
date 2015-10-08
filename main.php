<?php
	require_once 'autoload.php';

	$searchboot = new Search();
	$searchboot->colectData();
	$searchboot->generateReport();
?>