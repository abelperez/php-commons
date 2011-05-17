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
 * ArrayMultimap is a Multimap backed with an associative array of key and ArraySet 
 * of value pairs. 
 *
 * @package mindplex-commons-collections
 * @author Abel Perez
 */
class SetMultimap implements Multimap
{
    /** backing associative array for this multimap */
    private $map;

    /** the number of values contained in this multimap */
    private $size = 0;

    /**
     * Constructs this ArrayMultimap.
     */
    public function SetMultimap() {
        $this->map = array();
    }

    /**
     * Removes all the key value pairs from the multimap.
     */
    public function clear() {
        unset($this->map);
        $this->map = array();
        $this->size = 0;
    }

    /**
     * Checks if the multimap contains the specified key value pair.
     *
     * @param $key string key to search for in multimap
     * @param $value string value to search for in multimap
     *
     * @returns true if the multimap contains the specified key value pair.
     */
    public function containsEntry($key, $value) {
        if (! array_key_exists($key, $this->map)) return false;

        $set = $this->map[$key];
        foreach ($set->iterator() as $k => $v) {
            if ($value == $v) return true;
        }
        return false;
    }

    /**
     * Checks if the multimap contains the specified key.
     *
     * @param $key string key to search for in multimap
     *
     * @returns true if the multimap contains the specified key.
     */
    public function containsKey($key) {
        return array_key_exists($key, $this->map);
    }

    /**
     * Checks if the multimap contains the specified value.
     *
     * @param $value string value to search for in multimap
     *
     * @returns true if the multimap contains the specified value.
     */
    public function containsValue($value) {
        foreach ($this->map as $k => $v) {
            if (in_array($value, $v->toArray())) return true;
        }
        return false;
    }

    /**
     * Gets all the key value pairs in the multimap.
     */
    public function entries() {
        $entries = array();
        foreach ($this->map as $k => $v) {
            foreach ($v->iterator() as $value) {
                $entries[] = new Entry($k, $value);
            }
        }
        return $entries;
    }

    /**
     * Gets all the values that map to the specified key.
     *
     * @param $key string key to search for in multimap
     *
     * @returns the collection of values that the specified key maps to.
     */
    public function get($key) {
        if (! array_key_exists($key, $this->map)) return array();
        return $this->map[$key];
    }

    /**
     * Checks if the multimap contains key value pairs.
     *
     * @returns true if the multimap is empty. 
     */
    public function isEmpty() {
        return ($this->size <= 0);
    }

    /**
     * Get's all the keys in the multimap.
     *
     * returns array of all the keys in the multimap.
     */
    public function keys() {
        $keys = array();
        foreach ($this->map as $k => $v) {
            $keys[] = $k;
        }
        return $keys;
    }

    /**
     * Get's all the unique keys in the multimap.
     *
     * returns set of all the keys in the multimap.
     */
    public function keySet() {
        return array_unique($this->keys());
    }

    /**
     * Put's the specified key value pair in the multimap.
     *
     * @param $key string key to put in the multimap
     * @param $value string value to put in the multimap
     *
     * @returns true if the specified key value pair is put in the multimap.
     */
    public function put($key, $value) {
        if (! array_key_exists($key, $this->map)) {
            $set = new ArraySet();
            $set->add($value);
            $this->map[$key] = $set;
            $this->size++;
        } else {
            if ($this->map[$key]->add($value)) {
                $this->size++;
            }
        }
        return true;
    }

    /**
     * Put's all the specified key value pair in the multimap.
     *
     * @param $key string key to put in the multimap
     * @param $value array values to put in the multimap
     *
     * @returns true if all the specified key value pairs are put in the multimap.
     */
    public function putAll($key, $values) {
        $result = false;
        foreach ($values as $v) {
            $result = $this->put($key, $v);
        }
        return $result;
    }

    /**
     * Removes the specified key value pair in the multimap.
     *
     * @param $key string key to remove from the multimap
     * @param $value string value to remove from the multimap
     *
     * @returns true if the specified key value pair is removed from the multimap.
     */
    public function remove($key, $value) {
        if (! array_key_exists($key, $this->map)) return false;

        $result = false;
        $set = $this->map[$key];
        foreach ($set->iterator() as $k => $v) {
            if ($value == $v) {
                $set->remove($v);
                $result = true;
                $this->size--;
            }
        }
        $this->map[$key] = $set;
        return $result;
    }

    /**
     * Removes all the specified key value pair in the multimap.
     *
     * @param $key string key of values to remove from the multimap
     *
     * @returns true if all the entries that map to the specified key are removed.
     */
    public function removeAll($key) {
        if (! array_key_exists($key, $this->map)) return false;

        $result = false;
        $set = $this->map[$key];
        $count = $set->size();
        unset($this->map[$key]);
        $this->size = $this->size - $count;
        return $result;
    }

    /**
     * Replaces the specified key value pair in the multimap.
     *
     * @param $key string key of values to remove from the multimap
     * @param $value string value to search for in multimap
     *
     * @returns true if the values of the specified key are replaced.
     */
    public function replaceValues($key, $values) {
        if (! array_key_exists($key, $this->map)) return false;

        $set = $this->map[$key];
        $set->addAll($values);
        $this->map[$key] = $set;
    }

    /**
     * Get's the number of key value pairs in the multimap.
     *
     * @returns int the number of key value pairs in the multimap
     */
    public function size() {
        return $this->size;
    }

    /**
     * Get's all the values in the multimap.
     *
     * @returns all the values in the multimap.
     */
    public function values() {
        $values = array();
        foreach ($this->map as $k => $set) {
            $values[] = $set->toArray();
        }
        return $values;
    }
}

?>