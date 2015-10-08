<?php
	class Sales {

		private $arrSales;

		function __construct() {
			$this->arrSales = array();
		}

		/**
		 * [getTotalSales Performs the total count of sales]
		 * @method getTotalSales
		 * @return [integer] [Total number of sales]
		 */
		public function getTotalSales() {
			return count($this->arrSales);
		}

		/**
		 * [addSale Add a new sale from the list]
		 * @method addSale
		 * @param  [integer]  $id [Sale identificator]
		 * @param  [string]  $salesman_name [Salesman_name to maket the sale]
		 * @param  [string]  $items [Items of sale]
		 */
		public function addSale($id, $salesman_name, $items) {
			$objSale = new stdClass();
			$objSale->id = $id;
			$objSale->salesman_name = trim($salesman_name);
			$objSale->total = 0;

		   	$brackets = array('[',']');
			$itemsSale = str_replace($brackets, '', $items);

		   	if(trim($itemsSale)) {
		   		foreach (explode(',', $itemsSale) as $item) {
		   			$arrItem = explode('-', $item);
		   			$objItem = new stdClass();
		   			$objItem->id = $arrItem[0];
		   			$objItem->amount = $arrItem[1];
		   			$objItem->price = $arrItem[2];
					$objSale->total += (float) str_replace(' ','', $objItem->price);
		   		}
		   	}
			array_push($this->arrSales, $objSale);
		}

		/**
		 * [getSalesBySalesmanName description]
		 * @method getSalesBySalesmanName
		 * @param  [string] $name [Name for salesman]
		 * @return [array] [Total sales for the salesman]
		 */
		public function getSalesBySalesmanName($name) {
			$objResponse = array();
			foreach($this->arrSales as $objSale) {
				if($objSale->salesman_name == $name)
					array_push($objResponse, $objSale->total);
			}
			return $objResponse;
		}

		/**
		 * [getAllSalesman get all salesman to maketed a sal]
		 * @method getAllSalesman
		 * @return [array] [Whit all salesman name to maketed a sale]
		 */
		public function getAllSalesman() {
			$objResponse = array();
			foreach($this->arrSales as $objSale) {
				array_push($objResponse, $objSale->salesman_name);
			}
			return array_unique($objResponse);
		}

		/**
		 * [getSellersAmount Calculate the total os salesman sales]
		 * @method getSellersAmount
		 * @return [array] [Content object with salesman_name and sum of sales]
		 */
		public function getSellersAmount() {
			$objResponse = array();
			$arrSalesman = $this->getAllSalesman();
			foreach($arrSalesman as $name) {
				$obj = new stdClass();
				$obj->name = $name;
				$obj->total = array_sum($this->getSalesBySalesmanName($name));
				array_push($objResponse, $obj);
			}

			return $objResponse;
		}

		/**
		 * [getMostExpensiveSale Return the most expensive sale registred]
		 * @method getMostExpensiveSale
		 * @return [object] [sale]
		 */
		public function getMostExpensiveSale() {
			usort($this->arrSales, function($a, $b) {
				if($a->total == $b->total)
					return 0;
				return $a->total < $b->total ? 1 : -1;
			});
			return $this->arrSales[0];
		}
	}
?>
