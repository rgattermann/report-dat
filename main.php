<?php
	require_once 'autoload.php';

	$num =  preg_replace( '/[^0-9]/', '', '4000. 99');
	//only remove white spaces
	var_dump($num);die;
	$cpf_cnpj = new ValidaCPFCNPJ('77692868000124');
 
// Verifica se o CPF ou CNPJ é válido
if ( $cpf_cnpj->valida() ) {
	echo 'CPF ou CNPJ válido'; // Retornará este valor
} else {
	echo 'CPF ou CNPJ Inválido';
}
	//var_dump(Document::validate('77692868000124', 2));
	// try {
	// 	$searchboot = new Search();
	// 	$searchboot->watch();
	// } catch (Exception $e) {
	//     echo $e->getMessage().PHP_EOL;
	// }
?>