<?php
	class Sales {

		private $arrSales;

		function __construct() {
			$this->arrSales = array();
		}

		/**
		 * [getTotalSales description]
		 * @method getTotalSales
		 * @return [type]        [description]
		 */
		public function getTotalSales() {
			return count($this->arrSales);
		}

		/**
		 * [addSale description]
		 * @method addSale
		 * @param  [type]  $id            [description]
		 * @param  [type]  $salesman_name [description]
		 * @param  [type]  $items         [description]
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
		   			$objSale->total += (float) $objItem->price;
		   		}
		   	}
			array_push($this->arrSales, $objSale);
		}

		/**
		 * [getSalesBySalesmanName description]
		 * @method getSalesBySalesmanName
		 * @param  [type]                 $name [description]
		 * @return [type]                       [description]
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
		 * [getAllSalesman description]
		 * @method getAllSalesman
		 * @return [type]         [description]
		 */
		public function getAllSalesman() {
			$objResponse = array();
			foreach($this->arrSales as $objSale) {
				array_push($objResponse, $objSale->salesman_name);
			}
			return array_unique($objResponse);
		}

		/**
		 * [getSellersAmount description]
		 * @method getSellersAmount
		 * @return [type]           [description]
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
		 * [getMostExpensiveSale description]
		 * @method getMostExpensiveSale
		 * @return [type]               [description]
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
