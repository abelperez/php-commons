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
 * HtmlSelect contains methods that are 
 * common and useful and working with 
 * HTML Select tags.
 *
 * @package mindplex-commons-web
 * @author Abel Perez
 */
class HtmlSelect
{
	/**
	 * Renders an HTML Select tag with the value of the options based on the specified 
	 * array of options.  The selected option if any is based on the specified 
	 * selectedOption parameter. 
	 *
	 * @param string id the id for the newly created HTML Select tag.
	 *
	 * @param array $options a collection of key value pairs that make up the options id 
	 * and values for the HTML Select tag.
	 *
	 * @param string $selectedOption the option that should be rendered as a selected 
	 * option in the HTML Select tag.
	 *
	 * @returns a complient XHTML Select tag populated with options based on the specified 
	 * options.
	 */
	public static function render($id, $options, $selectedOption = '') {
	
		// open select tag
		$html = '<select id="'.$id.'" name="'.$id.'" class="dropdown">';
		
		// add options
		foreach ($options as $k => $v) {
			$html .= ($k != $selectedOption) 
				? '<option value="'.$v.'">'.$k.'</option>'
				: '<option value="'.$v.'" selected="selected">'.$k.'</option>';
		}
		
		// close select tag
		$html .= '</select>';
		return $html;
	}
}

?>