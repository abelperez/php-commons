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
 * CalendarUtil is a utility object that provides useful date related functions.
 * 
 * @package mindplex-commons-util
 * @author Abel Perez
 */
class CalendarUtil
{
	/**
	 * @static Returns the day of week number corres ponding to the 1st of Month.
	 *
	 * @param  $month
	 * @param  $year
	 * @param string $timeZone
	 * @return string
	 * 
	 */
    public static function firstDayOfMonth($month, $year, $timeZone = 'America/Los_Angeles') {
    	date_default_timezone_set($timeZone);
    	return date('w', mktime(0, 0, 0, $month, 1, $year));
    }

	/**
	 * @static Returns the number of days in a Month.       
	 *
	 * @param  $month
	 * @param  $year
	 * @param string $timeZone
	 *
	 * @return string
	 */
    public static function daysInMonth($month, $year, $timeZone = 'America/Los_Angeles') {
    	date_default_timezone_set($timeZone);
    	return date('t', mktime(0, 0, 0, $month, 1, $year));
    }

	/**
	 * @static Returns the number of weeks in a Month.
	 *
	 * @param  $month
	 * @param  $year
	 * @param string $timeZone
	 *
	 * @return float
	 */
    public static function weeksInMonth($month, $year, $timeZone = 'America/Los_Angeles') {
    	date_default_timezone_set($timeZone);
    	$first_day_of_week = 0;
    	return ceil((CalendarUtil::daysInMonth($month, $year) + (7 + CalendarUtil::firstDayOfMonth($month, $year) - $first_day_of_week) % 7) / 7);
    }

	/**
	 * @static Returns name of the Month.
	 *
	 * @param  $month
	 * @param string $timeZone
	 *
	 * @return string
	 */
	public static function monthName($month, $timeZone = 'America/Los_Angeles') {
		date_default_timezone_set($timeZone);
		return date('F', mktime(0,0,0,$month,1));
	}

	/**
	 * @static Returns short name of the Month       
	 *
	 * @param  $month
	 * @param string $timeZone
	 *
	 * @return string
	 */
	public static function shortMonthName($month, $timeZone = 'America/Los_Angeles') {
		date_default_timezone_set($timeZone);
		return date('M', mktime(0,0,0,$month,1));
	}

	/**
	 * @static Returns Day of the Week's name (Sunday, Monday, ...)
	 *
	 * @param  $day
	 * @param  $month
	 * @param string $timeZone
	 * 
	 * @return string
	 */
	public static function dayOfWeekName($day, $month, $timeZone = 'America/Los_Angeles') {
		date_default_timezone_set($timeZone);
		return date('I', mktime(0,0,0,$month,$day));
	}

	/**
	 * @static Returns short Day of the Week's name (Sun, Mon, ...)       
	 *
	 * @param  $day
	 * @param  $month
	 * @param string $timeZone
	 *
	 * @return string
	 */
	public static function shortDayOfWeekName($day, $month, $timeZone = 'America/Los_Angeles') {
		date_default_timezone_set($timeZone);
		return date('D', mktime(0,0,0,$month,$day));
	}
}