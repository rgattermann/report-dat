<?php
	class Sellers {

		private $arrSellers;

		function __construct() {
			$this->arrSellers = array();
		}

		/**
		 * [getTotalSellers Performs the total count of salesman]
		 * @method getTotalSellers
		 * @return [intger] [Total number os salesman]
		 */
		public function getTotalSellers() {
			return count($this->arrSellers);
		}

		/**
		 * [addSalesman Add a new salesman from the list]
		 * @method addSalesman
		 * @param  [string] $cpf    [CPF document]
		 * @param  [string] $name   [Salesman name]
		 * @param  [string] $salary [Salary]
		 */
		public function addSalesman($cpf, $name, $salary) {
			try {
				// if(!Document::validate($cpf))
				// 	throw new Exception('Invalid CPF document');

				$objSalesman = new stdClass();
				$objSalesman->cpf = $cpf;
				$objSalesman->name = trim($name);
				$objSalesman->salary = (float) str_replace(' ','', $salary);

				$objSalesman->total_sales = 0;
				array_push($this->arrSellers, $objSalesman);
				return true;
			} catch (Exception $e) {
			    return false;
			}
		}

		/**
		 * [getByName get data os salesman filtered by name]
		 * @method getByName
		 * @param  [string] $name [salesman name]
		 * @return [objec] [Salesman]
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
		 * [updateTotalSales Update total sales from salesman]
		 * @method updateTotalSales
		 * @param  [string] $name [Salemsan anme]
		 * @param  [float] $sale [Value of total sales]
		 * @return [boolean]
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
		 * [getWorstSeller Return the worst seller registred]
		 * @method getWorstSeller
		 * @return [object] [seller]
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
