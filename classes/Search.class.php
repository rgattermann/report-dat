<?php
	class Search {

		private $input_dir;
		private $output_dir;
		private $arrSellers;

		public function __construct() {
			$this->input_dir = 'data/in';
			$this->output_dir = 'data/out';
			$this->arrSellers = array();
		}

		public function colectData() {

			$modelSellers = new Sellers();
			$modelConsumers = new Consumers();
			$modelSales = new Sales();
			
			$dir = new DirectoryIterator(realpath($this->input_dir));
			
			foreach($dir as $fileinfo) {
			    if(!$fileinfo->isDot()) {
			    	$filename = $fileinfo->getFilename();
			    	$pathFile = $this->input_dir.'/'.$filename;
			    	$ext = pathinfo($filename, PATHINFO_EXTENSION);

			    	if(in_array($ext, array('dat'))) {
			    		$file_handle = fopen($pathFile, 'r');
						while(!feof($file_handle)) {
						   $line = fgets($file_handle);

						   $arrLine = explode('ç', $line);
						   if($arrLine[0] == '001') {
							   	$cpf = $arrLine[1];
							   	$name = $arrLine[2];
							   	$salary = $arrLine[3];
							   	$modelSellers->addSalesman($cpf, $name, $salary);
						   }
						   elseif($arrLine[0] == '002') {
						   	$cnpj = $arrLine[1];
						   	$name = $arrLine[2];
						   	$business_area = $arrLine[3];

						   	$modelConsumers->addConsumer($cnpj, $name, $business_area);
						   } elseif($arrLine[0] == '003') {
						   	$id = $arrLine[1];
						   	$items = $arrLine[2];
						   	$salesman_name = $arrLine[3];

						   	$modelSales->addSale($id, $salesman_name, $items);

						   }
						}
						fclose($file_handle);
					}
			    }
			}

			$arrSellersAmount = $modelSales->getSellersAmount();
			foreach ($arrSellersAmount as $obj) {
				$modelSellers->updateTotalSales($obj->name, $obj->total);
			}

			$this->totalConsumers = $modelConsumers->getTotalConsumers();
			$this->totalSellers = $modelSellers->getTotalSellers();
			$this->expensivesaleID = $modelSales->getMostExpensiveSale()->id;
			$this->worstSellerName = $modelSellers->getWorstSeller()->name;
		}

		public function generateReport() {
			echo "Number of clients: ".$this->totalConsumers."\n\r";
			echo "Number of salesman: ".$this->totalSellers."\n\r";
			echo "The most expensive sale is the sale: ".$this->expensivesaleID."\n\r";
			echo "The worst salesman ever is: ".$this->worstSellerName."\n\r";
		}

		

		private function gravarArquivoTexto( $strArquivo, $strTexto, $bolApagarSeJaExiste = false, $bolUTF8 = true ) {
		 
			if ( !is_dir( dirname( $strArquivo ) ) )
			{
				mkdir( dirname( $strArquivo ), 0755, true );
			}
		 
			$strModo = ($bolApagarSeJaExiste) ? "w" : "a";
		 
			$criarArquivo = (!is_file( $strArquivo ) );
			$objTxt = fopen( $strArquivo, $strModo );
			if ( $criarArquivo && $bolUTF8 )
			{
				//UTF-8
				fwrite( $objTxt, pack( "CCC", 0xef, 0xbb, 0xbf ) );
			}
			fwrite( $objTxt, $strTexto );
			fclose( $objTxt );
		}
		
		private function lerArquivoTexto($strArquivo) {
			if(is_file($strArquivo)) {
				$objTxt = fopen( $strArquivo, "r" );
				$texto = fread( $objTxt, filesize( $strArquivo ) );
				fclose($objTxt);
				return $texto;
			}
		}

		// //Carregando a classe e instanciando
		// require("classes/clsArquivo.php");
		// $objArquivo = new clsArquivo();
		 
		// //Lendo um arquivo e armazenando o conteúdo em uma variável:
		// $conteudo = $objArquivo->lerArquivoTexto("C:\Arquivo.txt");
		 
		// //Gravando o conteúdo de uma variável em um arquivo texto
		// $objArquivo->gravarArquivoTexto("C:\Outro_Arquivo.txt", $conteudo);
	}
?>