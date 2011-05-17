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
 * DateUtil is a utility object that provides useful date related functions.
 *
 * @package mindplex-commons-util
 * @author Abel Perez
 */
class DateUtil
{
    /*
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       Date Math Operations
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     */

    /**
     * Subtracts the specified number of days from the current date.
     *
     * @param int $days the number of days to subtract
     *
     * @returns the current date minus the specified number of days.
     */
    public static function subtract($days = 0) {
        return date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $days, date("Y")));
    }

    /**
     * Adds the specified number of days to the current date.
     *
     * @param int $days the number of days to add
     *
     * @returns the current date plus the specified number of days.
     */
    public static function add($days = 0) {
        return date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") + $days, date("Y")));
    }

    /**
     * Substracts the specified amount months from the current date. 
     *
     * Note: if anything other than integer value is specified as the months 
     * parameter, this function will default the value of months to 0 and return
     * the current date. 
     */
    public static function subtractMonths($months) {
        if (! is_int($months)) {
            $months = 0;
        }
        $today = date('Y-m-d');
        $newdate = strtotime('-'.$months.' month', strtotime($today));
        return date('Y-m-d' , $newdate);
    }

    /**
     * Adds the specified amount months from the current date. 
     *
     * Note: if anything other than integer value is specified as the months 
     * parameter, this function will default the value of months to 0 and return
     * the current date. 
     */
    public static function addMonths($months) {
        if (! is_int($months)) {
            $months = 0;
        }
        $today = date('Y-m-d');
        $newdate = strtotime('+'.$months.' month', strtotime($today));
        return date('Y-m-d' , $newdate);
    }

    /**
     * Subtracts the specified minutes from the current time.
     *
     * @param $minutes the minutes to subtract
     *
     * @return the current time minus the specified minutes.
     */
    public static function subtractMinutes($minutes = 0) {
        return date('Y-m-d H:i:s', mktime(date('H'), date('i') - $minutes,
                date('s'), date('m'), date('d'), date('Y')));
    }

    /*
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       Calendar Operations
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     */

    /**
     * Get current date in canonical format  format: Y-m-d H:i:s
     */
    public static function now() {
        return date('Y-m-d H:i:s ');
    }

    public static function standard_date($fmt = 'DATE_RFC822', $time = '') {
        $formats = array(
                'DATE_ATOM'	=> '%Y-%m-%dT%H:%i:%s%Q',
                'DATE_COOKIE' => '%l, %d-%M-%y %H:%i:%s UTC',
                'DATE_ISO8601' => '%Y-%m-%dT%H:%i:%s%O',
                'DATE_RFC822' => '%D, %d %M %y %H:%i:%s %O',
                'DATE_RFC850' => '%l, %d-%M-%y %H:%m:%i UTC',
                'DATE_RFC1036' => '%D, %d %M %y %H:%i:%s %O',
                'DATE_RFC1123' => '%D, %d %M %Y %H:%i:%s %O',
                'DATE_RSS' => '%D, %d %M %Y %H:%i:%s %O',
                'DATE_W3C' => '%Y-%m-%dT%H:%i:%s%Q');

        if ( ! isset($formats[$fmt])) {
            return FALSE;
        }

        return mdate($formats[$fmt], $time);
    }

    /**
     * Get the first day of the current week format: Y-m-d
     */
    public static function firstDayOfWeek() {
        return date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - date('w'), date('Y')));
    }

    /**
     * Get the first day of the current month format: Y-m-d
     */
    public static function firstDayOfMonth() {
        return date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
    }

    /**
     * Get the first day of the current year format: Y-m-d
     */
    public static function firstDayOfYear() {
        return date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
    }

    public static function daysInMonth($month = 0, $year = '') {
        if ($month < 1 OR $month > 12) {
            return 0;
        }

        if ( ! is_numeric($year) OR strlen($year) != 4) {
            $year = date('Y');
        }

        if ($month == 2) {
            if ($year % 400 == 0 OR ($year % 4 == 0 AND $year % 100 != 0)) {
                return 29;
            }
        }

        $days_in_month	= array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        return $days_in_month[$month - 1];
    }

    /*
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       Set Operations
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     */

    /**
     * Creates a range of dates that starts at the current date and goes forward 
     * or backward the specified number of days.
     *
     * @param string $days the length of the range in days
     * @param string $forward the direction of the range i.e. forward/previous.
     */
    public static function range($days, $forward = true) {
        $dates = array();

        if ($forward) {
            for ($delta = 1; $delta <= $days; $delta++) {
                $dates[] = DateUtil::add($delta);
            }
        } else {
            for ($delta = $days; $delta >= 0; $delta--) {
                $dates[] = DateUtil::subtract($delta);
            }
        }
    }

    /**
     * Gets the date difference between the specified dates.
     */
    public static function difference($format, $start, $end) {
        $s = explode($format, $start);
        $e = explode($format, $end);
        return (gregoriantojd($e[0], $e[1], $e[2]) - gregoriantojd($s[0], $s[1], $s[2]));
    }

    /*
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       Deprecated Operations
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     */

    /**
     * Available for backward compatibility.
     *
     * @deprecated in favor of difference()
     */
    public static function dateDiff() {
        return DateUtil::difference();
    }

    /*
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       Format Operations
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     */

    public static function unixToHuman($time = '', $seconds = FALSE, $format = 'us') {
        $date  = date('Y', $time).'-'.date('m', $time).'-'.date('d', $time).' ';

        if ($format == 'us') {
            $date .= date('h', $time).':'.date('i', $time);

        } else {
            $date .= date('H', $time).':'.date('i', $time);
        }

        if ($seconds) {
            $date .= ':'.date('s', $time);
        }

        if ($format == 'us') {
            $date .= ' '.date('A', $time);
        }

        return $date;
    }

    /**
     * Converts the specified MySQL Timestamp to Unix timestamp.
     *
     * @param string MySQL timestamp
     * @return integer specified MySQL timestamp as a unix timestamp.
     */
    public static function mysqlToUnix($time = '') {
        // We'll remove certain characters for backward compatibility
        // since the formatting changed with MySQL 4.1
        // YYYY-MM-DD HH:MM:SS

        date_default_timezone_set('America/Los_Angeles');

        $time = str_replace('-', '', $time);
        $time = str_replace(':', '', $time);
        $time = str_replace(' ', '', $time);

        // YYYYMMDDHHMMSS
        return  mktime(
                substr($time, 8, 2),
                substr($time, 10, 2),
                substr($time, 12, 2),
                substr($time, 4, 2),
                substr($time, 6, 2),
                substr($time, 0, 4));
    }

    /**
     * Take a date in yyyy-mm-dd format and return it to the user in a PHP date.
     *
     * @param MySQL date
     * @param format of the return date
     * @return PHP date
     */
    public static function mysqlToHuman($date, $format, $timeZone = 'America/Los_Angeles') {
        $dateTime = new DateTime($date, new DateTimeZone($timeZone));
        return $dateTime->format($format);
    }
}

?>
