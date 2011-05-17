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
 * NumberUtil is a utility object that provides useful number related functions.
 *
 * @package mindplex-commons-util
 * @author Abel Perez
 */
class NumberUtil
{
    /**
     * Get's the byte format of the specified number and adds the appropriate suffix.
     *
     * @param numeric the numeric value to format.
     * @return string byte formated number.
     */
    function byteFormat($num) {

        if ($num >= 1000000000000) {
            $num = round($num / 1099511627776, 1);
            $unit = 'TB';

        } elseif ($num >= 1000000000) {
            $num = round($num / 1073741824, 1);
            $unit = 'GB';

        } elseif ($num >= 1000000) {
            $num = round($num / 1048576, 1);
            $unit = 'MB';

        } elseif ($num >= 1000) {
            $num = round($num / 1024, 1);
            $unit = 'KB';

        } else {
            $unit = $CI->lang->line('bytes');
            return number_format($num).' '.$unit;
        }

        return number_format($num, 1).' '.$unit;
    }
}

?>