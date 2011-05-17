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
 * CsvUtil contains methods that are common and useful when working with CSV files.
 *
 * @package mindplex-commons-util
 * @author Abel Perez 
 */
class CsvUtil
{
	/**
	 * Converts implicit tab and new line characters into their literal equivalants.
	 * For example \t and \n.
	 *
	 * @param $data the data to clean up.
	 */
	protected function clean(&$data) {
		$data = preg_replace("/\t/", "\\t", $data);
		$data = preg_replace("/\r?\n/", "\\n", $data);
	}
	
	/**
	 * Exports the specified collection to tab delimited data as an Excel file.
	 *
	 * @param $collection the collection to transform into a CSV and Excel 
	 * compatible file.
	 */
	public function export($collection) {
	
		$output = '';
		
		$head = TRUE;
		foreach ($collection as $row) {
			if ($head) {
				# first row is column names
				$output .= implode("\t", array_keys($row))."\n";
				$head = FALSE;
			}
			array_walk($row, array($this, 'clean'));
			$output .= implode("\t", array_values($row))."\n";
		}
		
		return $output;	
	}

    /**
     * Creates a two dimensional array that contains the CSV data from the specified 
     * CSV file. 
     *
     * @param string $file the CSV file to open and convert into an array.
     *
     * @returns two dimensional array that contains the CSV data from the specified 
     * CSV file. 
     */
    public static function toArray($file) {

        $data = array();
        $handle = fopen($file, "r");

        while (($row = fgetcsv($handle, ",")) !== FALSE) {
            $data[] = $row;
        }

        fclose($handle);
        return $data;
    }
}

?>