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
 * HtmlDateRange contains methods for rendering HTML input fields as a date range 
 * widget.
 *
 * @package mindplex-commons-web
 * @author Abel Perez
 */
class HtmlDateRange extends HtmlComponent
{
	/** start date */
	private $start;
	
	/** end date */
	private $end;
	
	/**
	 * Constructs this date range.
	 */		
	public function HtmlDateRange() {
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
	public function bind() {
		$this->start = $this->get('start', date());
		$this->end = $this->get('end', date());	
	}
	
	/**
	 * Renders this date range as two HTML input fields, one as start date and the 
	 * other as end date.
	 *
	 * @returns HTML markup that represents a date range.
	 */	
	public function render() {
		return
		'<ul> 
			<li>
				<label for="start">start</label>'.
				$this->getStartDate().
			'</li>
			<li>
				<label for="end">end</label>'.
				$this->getEndDate().
			'</li> 
		</ul>';
	}
		
	/**
	 * Binds and displays this date range to the current HTTP GET/POST request and displays
	 * this date range as HTML.
	 *
	 * @returns HTML representation of this date range.
	 */		
	public static function bindAndDisplay() {
		$range = new HtmlDateRange();
		$range->start = $range->get('start', date('Y-m-d'));
		$range->end = $range->get('end', date('Y-m-d'));
		return $range->render();
	}

	/**
	 * Get's the HTML markup for the start date of this date range.
	 *
	 * @returns the HTML markup for the start date of this date range.
	 */	
	private function getStartDate() {
		return '<input id="start" name="start" type="text" value="'.
			$this->start.'" class="dateinput" />';	
	}
	
	/**
	 * Get's the HTML markup for the end date of this date range.
	 *
	 * @returns the HTML markup for the end date of this date range.
	 */
	private function getEndDate() {
		return '<input id="end" name="end" type="text" value="'.
			$this->end.'" class="dateinput" />';	
	}	
}

?>