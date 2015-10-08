<?php
	/**
	 *
	 */
	class Search {

		private $input_dir;
		private $output_dir;
		private $arrSellers;

		public function __construct() {
			$this->input_dir = 'data/in';
			$this->output_dir = 'data/out';
			$this->arrSellers = array();

			//erase files on diretoryes in out
		}

		/**
		 * [watch description]
		 * @method watch
		 * @return [type] [description]
		 */
		public function watch() {
			$totalFiles = 0;

			while(true) {
				$dir = new DirectoryIterator(realpath($this->input_dir));
				$numFiles = iterator_count($dir);

				if($numFiles != $totalFiles) {
					$totalFiles = $numFiles;

					foreach($dir as $fileinfo) {
					    if(!$fileinfo->isDot() && $fileinfo->isFile()) {
					    	$filename = $fileinfo->getFilename();
					    	$ext = pathinfo($filename, PATHINFO_EXTENSION);

					    	if(in_array($ext, array('dat')))
					    		$this->extractInfo($filename);
					    }
					}
				}
				sleep(3);
			}
		}

		/**
		 * [extractInfo description]
		 * @method extractInfo
		 * @param  [type]      $filename [description]
		 * @return [type]                [description]
		 */
		private function extractInfo($filename) {
	    	$modelSellers = new Sellers();
			$modelConsumers = new Consumers();
			$modelSales = new Sales();

			$pathFile = $this->input_dir.'/'.$filename;

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

			$arrSellersAmount = $modelSales->getSellersAmount();
			foreach($arrSellersAmount as $obj) {
				$modelSellers->updateTotalSales($obj->name, $obj->total);
			}

			$this->totalConsumers = $modelConsumers->getTotalConsumers();
			$this->totalSellers = $modelSellers->getTotalSellers();
			$this->expensivesaleID = $modelSales->getMostExpensiveSale()->id;
			$this->worstSellerName = $modelSellers->getWorstSeller()->name;
			$this->generateReport(basename($filename, '.'.pathinfo($filename, PATHINFO_EXTENSION)));
		}

		/**
		 * [generateReport description]
		 * @method generateReport
		 * @param  [type]         $filename [description]
		 * @return [type]                   [description]
		 */
		private function generateReport($filename) {
			$arrReportsLines = array();
			array_push($arrReportsLines, 'Number of clients: '.$this->totalConsumers);
			array_push($arrReportsLines, 'Number of salesman: '.$this->totalSellers);
			array_push($arrReportsLines, 'The most expensive sale is the sale: '.$this->expensivesaleID);
			array_push($arrReportsLines, 'The worst salesman ever is: '.$this->worstSellerName);

			$content = implode(PHP_EOL, $arrReportsLines);
			$reportFilename = $filename.'.done.dat';

			$this->saveFile($reportFilename, $content);
		}

		/**
		 * [saveFile description]
		 * @method saveFile
		 * @param  [type]   $filename [description]
		 * @param  [type]   $content  [description]
		 * @return [type]             [description]
		 */
		private function saveFile($filename, $content) {
			$fullpathFile = $this->output_dir.'/'.$filename;
			$objFile = fopen($fullpathFile, 'w');
			fwrite($objFile, $content);
			fclose($objFile);
		}
	}
?>
