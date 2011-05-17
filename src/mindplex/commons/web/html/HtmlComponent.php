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
 * HtmlComponent contains methods that are common and useful for HTML components.
 *
 * @package mindplex-commons-web
 * @author Abel Perez
 */
class HtmlComponent
{
	/**
	 * Get's the value of the specified parameter from the current HTTP GET/POST 
	 * request.
	 *
	 * @param string $name the name of the parameter to get.
	 * @param string $value default value if the specified parameter is not present 
	 * in the HTTP request.
	 */
	function get($name, $value = NULL) {
		if (isset($_GET[$name])) {
			return trim(htmlspecialchars($_GET[$name]));
			
		} else if (isset($_POST[$name])) {
			return trim(htmlspecialchars($_POST[$name]));
			
		}
		return $value;
	}
}

?>