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
 * Aggregate contains methods for aggregating data into summary values e.g., sum, 
 * max, min avg, etc.
 *
 * @package mindplex-commons-collections
 * @author Abel Perez
 */
class Aggregate
{
    /**
     * Sum's the specified elemenets attributes that match the given attribute.  
     * The specified attribute must be a numeric property of the elements contains 
     * in the specified list of elements.
     *
     * @param array $elements the list of elements to sum up.
     * @param string $attribute the attribute to some up from the list of elements.
     *
     * @return the sum of the specified elements attributes that match the given 
     * attribute.
     */
    public static function sum($elements, $attribute) {
        if (! is_array($elements)) {
            if (! property_exists($elements, $attribute)) {
                $value = $elements->$attribute;
                if (! is_numeric($value)) return 0;
                return $value;
            }
        }

        $value = 0;
        foreach ($elements as $element) {
            if (is_numeric($element->$attribute)) {
                $value += $element->$attribute;
            }
        }
        return $value;
    }

    /**
     * Get's the max value of the specified elemenets attributes that match the 
     * given attribute. The specified attribute must be a numeric property of the 
     * elements contains in the specified list of elements.
     *
     * @param array $elements list of elements to get max from.
     * @param string $attribute the attribute to max up from the list of elements.
     *
     * @return the max value of the specified elements attributes that match the 
     * given attribute.
     */
    public static function max($elements, $attribute) {
        if (! is_array($elements)) {
            if (! property_exists($elements, $attribute)) {
                $value = $elements->$attribute;
                if (! is_numeric($value)) return 0;
                return $value;
            }
        }

        $value = 0;
        foreach ($elements as $element) {
            if (is_numeric($element->$attribute) &&
                    $value < $element->$attribute) {
                $value = $element->$attribute;
            }
        }
        return $value;
    }

    /**
     * Get's the min value of the specified elemenets attributes that match the 
     * given attribute. The specified attribute must be a numeric property of the 
     * elements contains in the specified list of elements.
     *
     * @param array $elements list of elements to get min from.
     * @param string $attribute the attribute to min down from the list of elements.
     *
     * @return the min value of the specified elements attributes that match the 
     * given attribute.
     */
    public static function min($elements, $attribute) {
        if (! is_array($elements)) {
            if (! property_exists($elements, $attribute)) {
                $value = $elements->$attribute;
                if (! is_numeric($value)) return 0;
                return $value;
            }
        }

        if (count($elements) === 0) return 0;

        $value = $elements[0]->$attribute;
        foreach ($elements as $element) {
            if (is_numeric($element->$attribute) &&
                    $value > $element->$attribute) {
                $value = $element->$attribute;
            }
        }
        return $value;
    }

    /**
     * Get's the average value of the specified elemenets attributes that match 
     * the given attribute. The specified attribute must be a numeric property 
     * of the elements contains in the specified list of elements.
     *
     * @param array $elements list of elements to get the average value from.
     * @param string $attribute the attribute to avg from the list of elements.
     *
     * @return the average value of the specified elements attributes that match 
     * the given attribute.
     */
    public static function avg($elements, $attribute) {
        if (! is_array($elements)) {
            if (! property_exists($elements, $attribute)) {
                $value = $elements->$attribute;
                if (! is_numeric($value)) return 0;
                return $value;
            }
        }

        if (count($elements) === 0) return 0;

        $value = 0;
        foreach ($elements as $element) {
            if (is_numeric($element->$attribute)) {
                $value += $element->$attribute;
            }
        }
        return ($value / count($elements));
    }
}

?>