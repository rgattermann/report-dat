<?php
	class Sellers {

		private $arrSellers;

		function __construct() {
			$this->arrSellers = array();
		}

		/**
		 * [getTotalSellers description]
		 * @method getTotalSellers
		 * @return [type]          [description]
		 */
		public function getTotalSellers() {
			return count($this->arrSellers);
		}

		/**
		 * [addSalesman description]
		 * @method addSalesman
		 * @param  [type]      $cpf    [description]
		 * @param  [type]      $name   [description]
		 * @param  [type]      $salary [description]
		 */
		public function addSalesman($cpf, $name, $salary) {
			try {
				$objSalesman = new stdClass();

				$objSalesman->cpf = $cpf;

				$objSalesman->name = trim($name);
				$objSalesman->salary = (float) $salary;
				$objSalesman->total_sales = 0;
				array_push($this->arrSellers, $objSalesman);
				return true;
			} catch (Exception $e) {
			    return false;
			}
		}

		/**
		 * [getByName description]
		 * @method getByName
		 * @param  [type]    $name [description]
		 * @return [type]          [description]
		 */
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

		/**
		 * [updateTotalSales description]
		 * @method updateTotalSales
		 * @param  [type]           $name [description]
		 * @param  [type]           $sale [description]
		 * @return [type]                 [description]
		 */
		public function updateTotalSales($name, $sale) {
			$objSeller = $this->getByName($name);
			$index = $objSeller->index;
			unset($objSeller->index);
			$objSeller->total_sales = $sale;

			if(!$this->arrSellers[$index])
				return false;

			$this->arrSellers[$index] = $objSeller;
			return true;
		}
		
		/**
		 * [getWorstSeller description]
		 * @method getWorstSeller
		 * @return [type]         [description]
		 */
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
