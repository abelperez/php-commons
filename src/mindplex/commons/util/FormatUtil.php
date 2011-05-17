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
 * FormatUtil contains utility methods for formatting data e.g., phone numbers, 
 * money, etc.
 *
 * @package mindplex-commons-util
 * @author Abel Perez 
 */ 
class FormatUtil 
{
	/**
	 * Formats the specified phone number into a human readable format (xxx)xxx-xxxx.
	 *
	 * @param $phone the phone number to format
	 *
	 * @return the specified phone formatted in human readable format.
	 */
	public static function formatPhone($phone = '') {
	
		$phone = preg_replace("/[^0-9]/", "", $phone);
	
		if (strlen($phone) == 7) {
			return preg_replace("/([0-9]{3})([0-9]{4})/", 
				"$1-$2", $phone);
			
		} elseif(strlen($phone) == 10) {
			return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", 
				"($1) $2-$3", $phone);
				
		} else {
			return $phone;
		}
	}
}

?>