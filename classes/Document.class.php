<?php

class Document {

	/**
	 * [validate validade a document]
	 * @method validate
	 * @param  [string] $doc [document number]
	 * @return [boolean]
	 */
	public static function validate($doc) {
		$doc = preg_replace( '/[^0-9]/', '', $doc);

		if(strlen($doc) === 11)
			return self::validateCPF($doc);
		elseif(strlen($doc) === 14)
			return self::validateCNPJ($doc);
		else
			return false;
	}

	/**
	 * [validateCPF Validade document CPF]
	 * @method validateCNPJ
	 * @param  [string] $cnpj [Document number]
	 * @return [boolean]
	 */
	protected function validateCPF($cpf) {
			$cpf = str_pad(ereg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);

			$cpfInvalidos = array('00000000000', '11111111111', '22222222222', '33333333333', '44444444444', '55555555555', '66666666666', '77777777777', '88888888888', '99999999999');
			if (strlen($cpf) != 11 || in_array($cpf, $cpfInvalidos)) {
				return false;
			}
			else {
				for($t = 9; $t < 11; $t++) {
					for($d = 0, $c = 0; $c < $t; $c++) {
						$d += $cpf{$c} * (($t + 1) - $c);
					}
					$d = ((10 * $d) % 11) % 10;

					if ($cpf{$c} != $d)
						return false;
				}
				return true;
			}
	}

	/**
	 * [validateCNPJ Validade document CNPJ]
	 * @method validateCNPJ
	 * @param  [string] $cnpj [Document number]
	 * @return [boolean]
	 */
	protected function validateCNPJ($cnpj) {
		$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);

		if (strlen($cnpj) != 14)
			return false;

		for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
			$soma += $cnpj{$i} * $j;
			$j = ($j == 2) ? 9 : $j - 1;
		}
		$resto = $soma % 11;
		if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
			return false;

		for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
			$soma += $cnpj{$i} * $j;
			$j = ($j == 2) ? 9 : $j - 1;
		}

		$resto = $soma % 11;
		return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
	}
}
