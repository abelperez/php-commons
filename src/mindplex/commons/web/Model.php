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
 * Model is a property container that allows easier access to model data in views.
 *
 * @package mindplex-commons-web
 * @author Abel Perez 
 */
class Model 
{
	/**
	 * array of properties for this model.
	 */
	private $properties;
	
	/**
	 * Constructs this model with array of default propeties.
	 */
	function Model($properties) {
		if (is_array($properties)) {
			$this->properties = $properties;
		}
	}
	
	/**
	 * Gets the specified property from this model.
	 */
	function getProperty($name) {
		if (array_key_exists($name, $this->properties)) {
			return $this->properties[$name];
		}
	}
	
	/**
	 * Sets the specified property for this model.
	 */
	function setProperty($name, $value) {
		if (isset($name) && isset($value)) {
			$this->properties[$name] = $value;
		}
		return $this;
	}
}