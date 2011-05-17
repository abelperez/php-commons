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
 * Multimap is similar to a Map but allows mapping multiple values with a single key.  
 *
 * @package mindplex-commons-collections
 * @author Abel Perez
 */
interface Multimap
{
    /**
     * Removes all the key value pairs from the multimap.
     */
    public function clear();

    /**
     * Checks if the multimap contains the specified key value pair.
     *
     * @param $key string key to search for in multimap
     * @param $value string value to search for in multimap
     *
     * @returns true if the multimap contains the specified key value pair.
     */
    public function containsEntry($key, $value);

    /**
     * Checks if the multimap contains the specified key.
     *
     * @param $key string key to search for in multimap
     *
     * @returns true if the multimap contains the specified key.
     */
    public function containsKey($key);

    /**
     * Checks if the multimap contains the specified value.
     *
     * @param $value string value to search for in multimap
     *
     * @returns true if the multimap contains the specified value.
     */
    public function containsValue($value);

    /**
     * Gets all the key value pairs in the multimap.
     */
    public function entries();

    /**
     * Gets all the values that map to the specified key.
     *
     * @param $key string key to search for in multimap
     *
     * @returns the collection of values that the specified key maps to.
     */
    public function get($key);

    /**
     * Checks if the multimap contains key value pairs.
     *
     * @returns true if the multimap is empty. 
     */
    public function isEmpty();

    /**
     * Get's all the keys in the multimap.
     *
     * returns array of all the keys in the multimap.
     */
    public function keys();

    /**
     * Get's all the unique keys in the multimap.
     *
     * returns set of all the keys in the multimap.
     */
    public function keySet();

    /**
     * Put's the specified key value pair in the multimap.
     *
     * @param $key string key to put in the multimap
     * @param $value string value to put in the multimap
     *
     * @returns true if the specified key value pair is put in the multimap.
     */
    public function put($key, $value);

    /**
     * Put's all the specified key value pair in the multimap.
     *
     * @param $key string key to put in the multimap
     * @param $value array values to put in the multimap
     *
     * @returns true if all the specified key value pairs are put in the multimap.
     */
    public function putAll($key, $values);

    /**
     * Removes the specified key value pair in the multimap.
     *
     * @param $key string key to remove from the multimap
     * @param $value string value to remove from the multimap
     *
     * @returns true if the specified key value pair is removed from the multimap.
     */
    public function remove($key, $value);

    /**
     * Removes all the specified key value pair in the multimap.
     *
     * @param $key string key of values to remove from the multimap
     *
     * @returns true if all the entries that map to the specified key are removed.
     */
    public function removeAll($key);

    /**
     * Replaces the specified key value pair in the multimap.
     *
     * @param $key string key of values to remove from the multimap
     * @param $value string value to search for in multimap
     *
     * @returns true if the values of the specified key are replaced.
     */
    public function replaceValues($key, $values);

    /**
     * Get's the number of key value pairs in the multimap.
     *
     * @returns int the number of key value pairs in the multimap
     */
    public function size();

    /**
     * Get's all the values in the multimap.
     *
     * @returns all the values in the multimap.
     */
    public function values();
}

/**
 * Entry is a simple container class that holds a key value pair.
 *
 * Multimap uses this class to expose its key value pairs outside of the map.
 */
class Entry
{
    /** */
    private $key;

    /** */
    private $value;

    /**
     * Constructs this entry with the specified key value pair.
     *
     * @param string entry key
     * @param string entry value
     */
    public function Entry($key, $value) {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * Get's this enties key.
     *
     * @returns string this enties key
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * Set's this enties key.
     *
     * @param string this enties key
     */
    public function setKey($key) {
        $this->key = $key;
    }

    /**
     * Get's this enties value.
     *
     * @returns string this enties value
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Set's this enties value.
     *
     * @returns string this enties value
     */
    public function setValue($value) {
        $this->value = $value;
    }
}

?>