<?php
	class Sales {

		private $arrSales;

		function __construct() {
			$this->arrSales = array();
		}

		public function getTotalSales() {
			return count($this->arrSales);
		}

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

		public function getSalesBySalesmanName($name) {
			$objResponse = array();
			foreach($this->arrSales as $objSale) {
				if($objSale->salesman_name == $name)
					array_push($objResponse, $objSale->total);
			}
			return $objResponse;
		}

		public function getAllSalesman() {
			$objResponse = array();
			foreach($this->arrSales as $objSale) {
				array_push($objResponse, $objSale->salesman_name);
			}
			return array_unique($objResponse);
		}

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