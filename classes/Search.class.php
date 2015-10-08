<?php
	class Search {

		private $input_dir;
		private $output_dir;

		public function __construct() {
			$this->input_dir = 'data/in';
			$this->output_dir = 'data/out';

			foreach (new DirectoryIterator($this->input_dir) as $fileInfo) {
			    if(!$fileInfo->isDot())
			        unlink($fileInfo->getPathname());
			}

			foreach (new DirectoryIterator($this->output_dir) as $fileInfo) {
			    if(!$fileInfo->isDot())
			        unlink($fileInfo->getPathname());
			}
		}

		/**
		 * [watch Watch input dir for files to read]
		 * @method watch
		 * @return
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
		 * [extractInfo Extract data from files]
		 * @method extractInfo
		 * @param  [string] $filename [Filename content data]
		 * @return [type]
		 */
		private function extractInfo($filename) {
	    	$modelSellers = new Sellers();
			$modelConsumers = new Consumers();
			$modelSales = new Sales();
			$arrSalesman = array();

			$pathFile = $this->input_dir.'/'.$filename;

			$fileContent = file($pathFile);
			natsort($fileContent);

			foreach($fileContent as $line) {
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

			   	$modelSales->addSale($id, $salesman_name, $items, $modelSellers->getAll());
			   }
			}

			$totalConsumers = $totalSellers = $expensivesaleID = 0;
			$worstSellerName = '';

			$totalSellers = $modelSellers->getTotalSellers();
			if($totalSellers > 0) {
				$arrSellersAmount = $modelSales->getSellersAmount();
				foreach($arrSellersAmount as $obj) {
					$modelSellers->updateTotalSales($obj->name, $obj->total);
				}
				$worstSellerName = $modelSellers->getWorstSeller()->name;
				$expensivesaleID = $modelSales->getMostExpensiveSale()->id;
			}

			$totalConsumers = $modelConsumers->getTotalConsumers();
			$reportFilename = basename($filename, '.'.pathinfo($filename, PATHINFO_EXTENSION));
			$this->generateReport($reportFilename, $totalConsumers, $totalSellers, $expensivesaleID, $worstSellerName);
		}

		/**
		 * [generateReport Generate a report peer file readed and save on output dir]
		 * @method generateReport
		 * @param  [string] $filename [Filename content report data]
		 * @return
		 */
		private function generateReport($filename, $totalConsumers, $totalSellers, $expensivesaleID, $worstSellerName) {
			$arrReportsLines = array();
			array_push($arrReportsLines, 'Number of clients: '.$totalConsumers);
			array_push($arrReportsLines, 'Number of salesman: '.$totalSellers);
			array_push($arrReportsLines, 'The most expensive sale is the sale: '.$expensivesaleID);
			array_push($arrReportsLines, 'The worst salesman ever is: '.$worstSellerName);

			$content = implode(PHP_EOL, $arrReportsLines);
			$reportFilename = $filename.'.done.dat';

			$this->saveFile($reportFilename, $content);
		}

		/**
		 * [saveFile Save file on disk]
		 * @method saveFile
		 * @param  [string]   $filename [Filename to save]
		 * @param  [string]   $content  [Content to save]
		 * @return
		 */
		private function saveFile($filename, $content) {
			$fullpathFile = $this->output_dir.'/'.$filename;
			$objFile = fopen($fullpathFile, 'w');
			fwrite($objFile, $content);
			fclose($objFile);
		}
	}
?>
