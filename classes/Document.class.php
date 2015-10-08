<?php
	class Document {

		public static function validate($doc, $type) {
			if($type = 1)
				return self::validadeCPF($doc);
			else
				return self::validateCNPJ($doc);
		}

		private function validadeCPF($cpf) {
				// Verifiva se o número digitado contém todos os digitos
			    $cpf = str_pad(ereg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);

				// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
				$cpfInvalidos = array('00000000000', '11111111111', '22222222222', '33333333333', '44444444444', '55555555555', '66666666666', '77777777777', '88888888888', '99999999999');
			    if (strlen($cpf) != 11 || in_array($cpf, $cpfInvalidos)) {
					return false;
			    }
				else {
					// Calcula os números para verificar se o CPF é verdadeiro
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
		 * [validateCNPJ description]
		 * @method validateCNPJ
		 * @param  [integer] $cnpj [description]
		 * @return [boolen] [description]
		 */
		private function validateCNPJ($cnpj) {
			// Deixa o CNPJ com apenas números
			$cnpj = preg_replace( '/[^0-9]/', '', $cnpj );

			// Garante que o CNPJ é uma string
			$cnpj = (string)$cnpj;

			// O valor original
			$cnpj_original = $cnpj;

			// Captura os primeiros 12 números do CNPJ
			$primeiros_numeros_cnpj = substr( $cnpj, 0, 12 );

			/**
			 * Multiplicação do CNPJ
			 *
			 * @param string $cnpj Os digitos do CNPJ
			 * @param int $posicoes A posição que vai iniciar a regressão
			 * @return int O
			 *
			 */
			function multiplica_cnpj( $cnpj, $posicao = 5 ) {
				// Variável para o cálculo
				$calculo = 0;

				// Laço para percorrer os item do cnpj
				for ( $i = 0; $i < strlen( $cnpj ); $i++ ) {
					// Cálculo mais posição do CNPJ * a posição
					$calculo = $calculo + ( $cnpj[$i] * $posicao );

					// Decrementa a posição a cada volta do laço
					$posicao--;

					// Se a posição for menor que 2, ela se torna 9
					if ( $posicao < 2 ) {
						$posicao = 9;
					}
				}
				// Retorna o cálculo
				return $calculo;
			}

			// Faz o primeiro cálculo
			$primeiro_calculo = multiplica_cnpj( $primeiros_numeros_cnpj );

			// Se o resto da divisão entre o primeiro cálculo e 11 for menor que 2, o primeiro
			// Dígito é zero (0), caso contrário é 11 - o resto da divisão entre o cálculo e 11
			$primeiro_digito = ( $primeiro_calculo % 11 ) < 2 ? 0 :  11 - ( $primeiro_calculo % 11 );

			// Concatena o primeiro dígito nos 12 primeiros números do CNPJ
			// Agora temos 13 números aqui
			$primeiros_numeros_cnpj .= $primeiro_digito;

			// O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
			$segundo_calculo = multiplica_cnpj( $primeiros_numeros_cnpj, 6 );
			$segundo_digito = ( $segundo_calculo % 11 ) < 2 ? 0 :  11 - ( $segundo_calculo % 11 );

			// Concatena o segundo dígito ao CNPJ
			$cnpj = $primeiros_numeros_cnpj . $segundo_digito;

			// Verifica se o CNPJ gerado é idêntico ao enviado
			if ( $cnpj === $cnpj_original ) {
				return true;
			}
		}
	}
?>