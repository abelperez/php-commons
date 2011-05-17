<?php

/**
 * Copyright (C) 2011 Mindplex Media, LLC.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
 * file except in compliance with the License. You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed
 * under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR
 * CONDITIONS OF ANY KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations under the License.
 */
 
/**
 * DataTable contains operations related to binding 
 * ActiveRecord entities to the jQuery DataTable Plugin.
 * 
 * @package mindplex-commons-web
 * @author Abel Perez
 */
class DataTable extends HtmlComponent
{	
	/**
	 * The data access object this data table uses to lookup the required data 
	 * to bind to.
	 */
	private $dao;
	
	/**
	 * Entity bind attributes.
	 */
	private $attributes;
	
	/**
	 * The data this table is bound to.
	 */
	private $data;
	
	/**
	 * The total count of records in the data table.
	 */
	private $totalCount = 0;
	
	/**
	 * The total count of records to show in the data table.
	 */
	private $totalDisplayCount = 10;
	
	/**
	 * Constructs this DataTable with the specified entity attributes.
	 *
	 * @param $dao the data access object that this data table uses to lookup the 
	 * required data to bind to.
	 * @param $attributes entity attributes to bind this data table to.
	 */
	public function DataTable($dao, $attributes) {
		if ($dao == NULL) throw new Exception("Data access object cannot be null.");
		$this->dao = $dao;
		$this->attributes = (! empty($attributes)) ? $attributes : $dao->getAttributeNames();
	}
	
	/**
	 * Get's the attributes configured for this data table.
	 *
	 * @return the attributes configured for this data table.
	 */
	public function getAttributes() {
		return ($this->attributes != NULL) ? $this->attributes : array();
	}
	
	/**
	 * Get's the data this table is bound to.
	 *
	 * @return the data this table is bound to.
	 */
	public function getData() {
		return ($this->data != NULL) ? $this->data : array(); 	
	}
	
	/**
	 * Get's the total count of records in the data table.
	 *
	 * @return the total count of records in the data table.
	 */
	public function getTotalCount() {
		return $this->totalCount;	
	}
	
	/**
	 * Get's total count of records to show in the data table.
	 *
	 * @return total count of records to show in the data table.
	 */
	public function getTotalDisplayCount() {
		return $this->totalDisplayCount;	
	}
	
	/**
	 * Binds this data table to the HTTP request parameters sent from the jQuery 
	 * DataTable Plugin and builds a dynamic SQL query used to generate the data for 
	 * the jQuery DataTable.
	 */
	public function bind() {
		
		/*
		 * Builds SELECT statement from the attributes of the data access object this 
		 * data table was constructed with.
		 */
		$select = $this->dao->selectAllExpression($this->getAttributes());
		
		/*
		 * Builds LIKE expression based on the specified search parameters.
		 */
		$like = '';
		$keyword = $this->get('sSearch', '');
		if ($keyword != null) {
			$like .= ' WHERE '.$this->dao->likeExpression(
					$keyword, $this->getAttributes(), 'OR');
		}
		
		/*
		 * Builds ORDER BY clause based on the specified attribute sort ordering.
		 */
		$order = '';
		if ($this->get('iSortCol_0', '') != '') {
			$attributes = $this->getAttributes();
			$sortAttributes = array();
			for ($i = 0; $i < intval( $this->get('iSortingCols') ); $i++) {
				$key = $attributes[intval($this->get('iSortCol_'.$i))];
				$val = $this->get('sSortDir_'.$i, 'ASC');
				$sortAttributes[$key] = $val;
			}
			$order = $this->dao->orderByExpression($sortAttributes, 'ASC', TRUE);
		}
		
		/*
		 * Builds LIMIT clause based on the specified limit range.
		 */
		$start = $this->get('iDisplayStart', '0');
		$length = $this->get('iDisplayLength', '10');
		$limit = $this->dao->limitExpression($start, $length);
		
		/*
		 * Binds this data table to the results of executing the dynamically 
		 * built query from the previous projection, search, sort and
		 * limit cases.
		 */
		$this->data = $this->dao->query($select.$like.$order.$limit); 
		$this->totalDisplayCount = $this->dao->found();
		$this->totalCount = $this->dao->count();
	}
	
	/**
	 * Builds a representation of the data contained in this data table as the JSON 
	 * required by the jQuery DataTable plugin.
	 *
	 * @return a representation of the data contained in this data table as JSON.
	 */
	public function toJson() {
		
		$js = '{';
		$js .= '"iTotalRecords": '.$this->getTotalCount().', ';
		$js .= '"iTotalDisplayRecords": '.$this->getTotalDisplayCount().', ';
		$js .= '"aaData": [ ';
		
		foreach ($this->getData() as $row) {
			$js .= "[";
			foreach ($this->getAttributes() as $attribute) {
				$js .= '"'.addslashes($row[$attribute]).'",';
			}
			$js = substr_replace($js, "", -1);
			$js .= "],";
		}
		
		$js = substr_replace($js, "", -1);
		$js .= '] }';
		return $js;		
	}	
}

?>
