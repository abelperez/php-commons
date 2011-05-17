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
 * Pager contains methods for paginating through a collection of elements.
 *
 * @package mindplex-commons-web
 * @author Abel Perez 
 */
class Pager 
{
	/** elements to page through */
	private $elements;
	
	/** page size */
	private $size = 10;
	
	/** page number */ 
	private $page = 1;
	
	/**
	 * Constructs this pager with the specified elements, page number and page size.
	 *
	 * @param array $elements collection of elements to page through.
	 * @param string page number of pages for this pager.
	 * @param string size page size for this pager.  
	 */ 	 
	public function Pager($elements, $page = 1, $size = 10) {
		
		if (! is_array($elements)) { 
			throw Exception('invalid collection of elements.');
		}
			
		$this->elements = $elements;
		$this->size = $size;
		
		if (! $this->isPageValid($page)) {
			$page = 1;
		}
		$this->page = $page; 
	}

	/**
	 * Get's the number of pages for this pager.
	 *
	 * @returns the number of pages for this pages.
	 */
	public function getPageCount() {
	
		if (empty($this->elements)) {
			return false;
		}
		return ceil(count($this->elements) / (float)$this->size);
	}

	/**
	 * Checks if the specified page number is valid i.e., within the range of pages 
	 * for this pager.
	 *
	 * @param int $page the page to check is acceptable.
	 *
	 * @returns true if the page is acceptable.
	 */
	public function isPageValid($page) {
		if ($page == null || 
			$page == '' ||
			$page <= 0 ||
			$page > $this->getPageCount()) {
			 
			return false;
		}
		return true;
	}

	/**
	 * Get's the elements contained within the current page of this pager.
	 *
	 * @returns the elements contained within the current page of this pager.
	 */
	public function elements() {
		
		$offset = ($this->page - 1) * $this->size;
		$length = count($this->elements) - $offset;
		if ($length > $this->size) {
			$length = $this->size;
		}
		return array_slice($this->elements, $offset, $length);
	}
	
	/**
	 * Returns True if the current page is the first page in this pager.
	 *
	 * @returns True if the current page is the first page in this pager.
	 */
	public function isFirstPage() {
		return ($this->page <= 1);
	}

	/**
	 * Returns True if the current page is the last page in this pager.
	 *
	 * @returns True if the current page is the last page in this pager.
	 */
	public function isLastPage() {
		return ($this->page >= $this->getPageCount());
	}
	
	/**
	 * Get's an HTML navigation bar based on the specified base url and pages 
	 * this pager contains.
	 *
	 * @returns navigation bar for this pager's pages.
	 */	
	public function navigation($url) {
		
		// append query string delimiter if needed
		if (strpos($url, '?') === false) {
			$url .= '?page=';
		} else {
			$url .= '&page=';
		}
		
		$html = '<div class="pagination">';
		$html .= $this->getPreviousLink($url);
		$html .= $this->getPageLinks($url);
		$html .= $this->getNextLink($url);
		$html .= '</div>';
		return $html;	
	}
	
	/**
	 * Get's an HTML anchor tag with the specified endoint as the href and the 
	 * specified copy as the anchors text.
	 * 
	 * @param string $endpoint href url for the anchor tag.
	 * @param string $copy the HTML anchors text.
	 *
	 * @returns an HTML anchor tag based on the specified endpoint and copy.
	 */
	private function link($endpoint, $copy) {
		return '<a href="'.$endpoint.'">'.$copy.'</a>';
	}
	
	/**
	 * Get's the previous page link for this pager.
	 *
	 * @param string $url the base url to set on the previous page link for this 
	 * pager.
	 *
	 * @returns the previous page link for this pager.
	 */	
	private function getPreviousLink($url) {
		if (! $this->isFirstPage()) {
			return $this->link($url.($this->page - 1), 'Previous');
		}
	}

	/**
	 * Get's the next page link for this pager.
	 *
	 * @param string $url the base url to set on the next page link for this pager.
	 *
	 * @returns the next page link for this pager.
	 */	
	private function getNextLink($url) {
		if (! $this->isLastPage()) {
			return $this->link($url.($this->page + 1), 'Next');
		}	
	}
	
	/**
	 * Get's the page links for this pager.
	 *
	 * @param string $url the base url to set on the page links for this pager.
	 *
	 * @returns the page links for this pager.
	 */
	private function getPageLinks($url) {
		
		$html = '';
		$pages = $this->getPageCount();
		
		for ($i = 1; $i <= $pages; $i++) {	
			// unlink current page
			if ($i == $this->page) {
				$html .= '<em><strong>'.$i.'</strong></em>';
				
			} else {
				$html .= '<a href="'.$url.$i.'">'.$i.'</a>';
			}
		}
		return $html;
	}		
}

?>