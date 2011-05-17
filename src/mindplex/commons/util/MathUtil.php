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
 * MathUtil contains statistical function for analyzing data.
 *
 * @package mindplex-commons-util
 * @author Abel Perez
 */
class MathUtil
{
    /**
     * Calculates the mean of the specified elements.
     *
     * @param array $elements list of element from a sample or population 
     * distribution.
     * @param boolean $population true if the element list is a population 
     * distribution.
     *
     * @return the mean of the specified elements.
     */
    public static function mean($elements) {
        $sum = array_sum($elements);
        $n = count($elements);
        return ($sum / $n);
    }

    /**
     * Calculates the variance of the specified elements. If the given elements 
     * is a population then the population standard deviation is calculated
     * otherwise the sample standard deviation.
     *
     * @param array $elements list of element from a sample or population 
     * distribution.
     * @param boolean $population true if the element list is a population 
     * distribution.
     *
     * @return the variance of the specified elements.
     */
    public static function variance($elements, $population = FALSE) {
        $variance = 0.0;
        $n = count($elements);
        $mean = Math::mean($elements);

        for ($i = 0; $i < $n; $i++) {
            $variance = $variance + ($elements[$i] - $mean) * ($elements[$i] - $mean);
        }

        if (! $population) {
            $n = ($n - 1.0);
        }

        return $variance / ($n);
    }

    /**
     * Calculates the standard deviation of the specified elements. If the given 
     * elements is a population then the population standard deviation is 
     * calculated otherwise the sample standard deviation.
     *
     * @param array $elements list of element from a sample or population 
     * distribution.
     * @param boolean $population true if the element list is a population 
     * distribution.
     *
     * @return the variance of the specified elements.
     */
    public static function stdv($elements, $population = FALSE) {

        $stdv = 0.0;
        $variance = 0.0;
        $n = count($elements);
        $mean = Math::mean($elements);
        $variance = Math::variance($elements, $population);

        $stdv = pow($variance, 0.5);
        // $stdv = sqrt($variance);

        return $stdv;
    }

    /**
     * Calculates the standard deviation of the specified elements. If the 
     * given elements is a population then the population standard deviation 
     * is calculated otherwise the sample standard deviation.
     *
     * @param array $elements list of element from a sample or population 
     * distribution.
     * @param boolean $population true if the element list is a population 
     * distribution.
     *
     * @return the variance of the specified elements.
     */
    public static function standardDeviation($elements, $population = FALSE) {
        return Math::stdv($elements, $population);
    }
}

?>