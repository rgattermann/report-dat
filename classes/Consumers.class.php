<?php
	class Consumers {

		private $arrConsumers;

		function __construct() {
			$this->arrConsumers = array();
		}

		/**
		 * [getTotalConsumers Performs the total count of consumers]
		 * @method getTotalConsumers
		 * @return [integer] [Total number of consumers]
		 */
		public function getTotalConsumers() {
			return count($this->arrConsumers);
		}

		/**
		 * [addConsumer Add a new consumer from the list]
		 * @method addConsumer
		 * @param  [number] $cnpj [Document consumer]
		 * @param  [string] $name [Consumer name]
		 * @param  [string] $business_area [Consumer business area]
		 */
		public function addConsumer($cnpj, $name, $business_area) {
			$objConsumer = new stdClass();
			$objConsumer->cnpj = $cnpj;
			$objConsumer->name = $name;
			$objConsumer->business_area = $business_area;
			$objConsumer->total_sales = 0;
			array_push($this->arrConsumers, $objConsumer);
		}
	}
?>
