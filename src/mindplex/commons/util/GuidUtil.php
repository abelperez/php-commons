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
 *
 *
 * @package mindplex-commons-util
 * @author Abel Perez
 */
class GuidGenerator
{
    public static function generate()
    {
        //e.g. output: 372472a2-d557-4630-bc7d-bae54c934da1
        //word*2-, word-, (w)ord-, (w)ord-, word*3
        $guidstr = "";
        for ($i=1;$i<=16;$i++) {
            $b = (int)rand(0,0xff);
            if ($i == 7) { $b &= 0x0f; $b |= 0x40; } // version 4 (random)
            if ($i == 9) { $b &= 0x3f; $b |= 0x80; } // variant
            $guidstr .= sprintf("%02s", base_convert($b,10,16));
            if ($i == 4 || $i == 6 || $i == 8 || $i == 10) { $guidstr .= '-'; }
        }
        return $guidstr;
    }
}


?>