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
 *
 *
 * @package mindplex-commons-web
 * @author Abel Perez
 */
class QueryDate extends HtmlComponent
{
	/** start date */
	private $start;
	
	/** end date */
	private $end;
	
	/**
	 * Constructs this date range.
	 */		
	public function QueryDate() {
	}
	
	/**
	 * Get's the start date for this date range.
	 *
	 * @returns start date for this date range.
	 */	
	public function getStart() {
		return $this->start;	
	}
	
	/**
	 * Get's the end date for this date range.
	 *
	 * @returns end date for this date range.
	 */	
	public function getEnd() {
		return $this->end;
	}
	
	/**
	 * Binds this date range to the current HTTP GET/POST request parameters that
	 * map to start and end date.
	 */	
	public static function bind() {
		$date = new QueryDate();
		$date->start = $date->get(START_DATE, date(DATE_FORMAT));
		$date->end = $date->get(END_DATE, date(DATE_FORMAT));
		return $date;	
	}
}

?>