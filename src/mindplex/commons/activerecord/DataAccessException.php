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
 * DataAccessException is a custom Exception that contains additonal information 
 * related to the result of an exception caused by accessing data from a data store.
 *
 * The following is a list of error codes that map to data access exceptions:
 *
 * <ul>
 * <li>100 find<li>
 * <li>101 findAll<li>
 * <li>102 findBy<li>
 * <li>103 daterange<li>
 * <li>104 attributes<li>
 * <li>105 joins<li>
 * <li>106 save, delete, update, destroy, reload<li>
 * <li>107 aggregates<li>
 * <li>108 predicates<li>
 * <li>109 in<li>
 * </ul>
 *
 * @package mindplex-commons-activerecord
 * @author Abel Perez
 */
class DataAccessException extends Exception
{
	/** inner exception */
	private $innerException;
	
	/**
	 * Constructs this exception and initializes it with the specified message, 
     * error code and inner exception.
	 */
	public function __construct($message, $errorCode = 0, Exception $innerException = null) {
		parent::__construct($message, $errorCode);
		
		if (! is_null($innerException)) {
			$this->innerException = $innerException;
		}
	}
	
	/**
	 * Get's the string representation of this exception.
	 *
	 * @returns the string representation of this exception.
	 */
	public function toString() {
		$string = __CLASS__ . ': ['.$this->code.']: '.$this->message;
		if (defined('__DEBUG__') && 
			! is_null($this->innerException)) { 
			$string .= ' -- inner exception: '.$this->innerException->getMessage();
		}
		return $string;
	}
}

?>