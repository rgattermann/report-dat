<?php
	class Sellers {

		private $arrSellers;

		function __construct() {
			$this->arrSellers = array();
		}

		public function getTotalSellers() {
			return count($this->arrSellers);
		}

		public function addSalesman($cpf, $name, $salary) {
			$objSalesman = new stdClass();
			$objSalesman->cpf = $cpf;
			$objSalesman->name = trim($name);
			$objSalesman->salary = $salary;
			$objSalesman->total_sales = 0;
			array_push($this->arrSellers, $objSalesman);
		}

		public function getByName($name) {
			$objResponse = null;
			foreach($this->arrSellers as $index => $objSalesman) {
				if($objSalesman->name == $name) {
					$objResponse = $objSalesman;
					$objResponse->index = $index;
				}
			}
			return $objResponse;
		}

		public function updateTotalSales($name, $sale) {
			$objSeller = $this->getByName($name);
			$index = $objSeller->index;
			unset($objSeller->index);
			$objSeller->total_sales = $sale;
			$this->arrSellers[$index] = $objSeller;
		}

		public function getWorstSeller() {
			usort($this->arrSellers, function($a, $b) {
				if($a->total_sales == $b->total_sales)
					return 0;
				return $a->total_sales > $b->total_sales ? 1 : -1;
			});
			return $this->arrSellers[0];
		}
	}
?>