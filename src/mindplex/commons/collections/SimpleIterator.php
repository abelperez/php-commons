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
 * Simple iterator provides an iterator API for traversing collections.
 *
 * @package mindplex-commons-collections
 * @author Abel Perez
 */
class SimpleIterator implements Iterator, Countable
{
    /** collection of items */
    private $items;

    /** the iterable collection's keys */
    private $keys;

    /** current iteration index */
    private $currentIndex = 0;

    /**
     * Constructs this iterator with the specified collection.
     */
    public function SimpleIterator($items) {
        if (! is_array($items)) {
            throw new Exception('invalid collection');
        }
        $this->items = $items;
        $this->keys = array_keys($this->items);
    }

    /*
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       Iterator Operations
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     */

    /**
     * Rewind the Iterator to the first element. Similar to the reset() 
     * function for arrays in PHP 
     *
     * @return void
     */
    public function rewind() {
        $this->currentIndex = 0;
    }

    /**
     * Return the current element. Similar to the current() function for 
     * arrays in PHP.
     *
     * @return mixed current element from the collection 
     */
    public function current() {
        $k = $this->keys[$this->currentIndex];
        return $this->items[$k];
    }

    /**
     * Return the identifying key of the current element. Similar to the key() 
     * function for arrays in PHP.
     *
     * @return mixed either an integer or a string 
     */
    public function key() {
        return $this->keys[$this->currentIndex];
    }

    /**
     * Move forward to next element. Similar to the next() function for arrays 
     * in PHP.
     *
     * @return void
     */
    public function next() {
        if ($this->currentIndex + 1 > sizeof($this->items)) {
            throw new Exception('no such element');
        }
        $k = $this->keys[$this->currentIndex++];
        return $this->items[$k];
    }

    /**
     * Check if there is a current element after calls to rewind() or next(). 
     * Used to check if we've iterated to the end of the collection.
     *
     * @return boolean FALSE if there's nothing more to iterate over 
     */
    public function valid() {
        return ($this->currentIndex + 1 <= sizeof($this->items));
    }

    /*
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       Countable Operations
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     */

    /**
     * Get's the count of items in this iterator.
     */
    public function count() {
        return count($this->items);
    }

    /*
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       Sorting Operations
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     */

    /**
     * Sort's the values in this iterator by natural order.
     */
    public function sort() {
        sort($this->items);
        $this->keys = array_keys($this->items);
        $this->rewind();
    }

    /**
     * Sort's the keys in this iterator by natural order.
     */
    public function ksort() {
        ksort($this->items);
        $this->keys = array_keys($this->items);
        $this->rewind();
    }

}

?>