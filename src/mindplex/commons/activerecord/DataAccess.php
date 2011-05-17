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
 * DataAccess is a singleton instance that holds
 * a handle to a database connection.
 *
 * @package mindplex-commons-activerecord
 * @author Abel Perez
 */
class DataAccess
{
    /** Sole instance */
    private static $instance = NULL;

    /**
     * Prevent direct instantiation of this singleton object.
     */
    private function __construct() {
    }

    /**
     * Get's a singleton instance of this object.
     *
     * @return object (PDO) singleton instance of this object.
     */
    public static function getInstance() {
        if (! self::$instance) {
            self::$instance = new PDO(DB_CONNECTION, DB_USER, DB_PASSWORD);
            self::$instance-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$instance;
    }

    /**
     * Prevent cloning of this object. 
     */
    private function __clone(){
    }
}

?>