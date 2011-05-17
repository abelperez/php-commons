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
 * ArraySet is a collection that contains no duplicate elements and at most one null 
 * element.  This Set models after the mathametical Set abstraction. This Set is backed 
 * by an array.
 *
 * @package mindplex-commons-collections
 * @author Abel Perez 
 */
class ArraySet implements Set
{
    /** Elements in this set */
    private $elements;

    /** the number of elements in this set */
    private $size = 0;

    /**
     * Constructs this set.
     */
    public function ArraySet() {
        $this->elements = array();
    }

    /**
     * Adds the specified element to this set if it is not already present.
     *
     * @param any $element
     *
     * @returns true if the specified element was added to this set.
     */
    public function add($element) {
        if (! in_array($element, $this->elements)) {
            $this->elements[] = $element;
            $this->size++;
            return true;
        }
        return false;
    }

    /**
     * Adds all of the elements in the specified collection to this set if 
     * they're not already present.
     *
     * @param array $collection
     *
     * @returns true if any of the elements in the specified collection 
     * where added to this set. 
     */
    public function addAll($collection) {
        $changed = false;
        foreach ($collection as $element) {
            if ($this->add($element)) {
                $changed = true;
            }
        }
        return $changed;
    }

    /**
     * Removes all the elements from this set.
     */
    public function clear() {
        $this->elements = array();
        $this->size = 0;
    }

    /**
     * Checks if this set contains the specified element. 
     *
     * @param any $element
     *
     * @returns true if this set contains the specified element.
     */
    public function contains($element) {
        return in_array($element, $this->elements);
    }

    /**
     * Checks if this set contains all the specified element.
     *
     * @param array $collection
     *
     * @returns true if this set contains all the specified element. 
     */
    public function containsAll($collection) {
        foreach ($collection as $element) {
            if (! in_array($element, $this->elements)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Checks if this set contains elements.
     *
     * @returns true if this set contains no elements. 
     */
    public function isEmpty() {
        return count($this->elements) <= 0;
    }

    /**
     * Get's an iterator over the elements in this set.
     *
     * @returns an iterator over the elements in this set.
     */
    public function iterator() {
        return new SimpleIterator($this->elements);
    }

    /**
     * Removes the specified element from this set.
     *
     * @param any $element
     *
     * @returns true if the specified element is removed.
     */
    public function remove($element) {
        if (! in_array($element, $this->elements)) return false;

        foreach ($this->elements as $k => $v) {
            if ($element == $v) {
                unset($this->elements[$k]);
                $this->size--;
                return true;
            }
        }
    }

    /**
     * Removes all the specified elements from this set.
     *
     * @param array $collection
     *
     * @returns true if all the specified elemensts are removed from this set. 
     */
    public function removeAll($collection) {
        $changed = false;
        foreach ($collection as $element) {
            if ($this->remove($element)) {
                $changed = true;
            }
        }
        return $changed;
    }

    /**
     * Retains the elements in this set that are in the specified collection.  
     * If the specified collection is also a set, this method effectively
     * modifies this set into the intersection of this set and the specified 
     * collection.
     *
     * @param array $collection
     *
     * @returns true if this set changed as a result of the specified collection.
     */
    public function retainAll($collection) {
        $changed = false;
        foreach ($this->elements as $k => $v) {
            if (! in_array($v, $collection)) {
                unset($this->elements[$k]);
                $this->size--;
                $changed = true;
            }
        }
        return $changed;
    }

    /**
     * Returns the number of elements in this set.
     *
     * @returns the number of elements in this set.
     */
    public function size() {
        return $this->size;
    }

    /**
     * Returns an array that contains all the elements in this set.
     *
     * @returns an array that contains all the elements in this set.
     */
    public function toArray() {
        $elements = $this->elements;
        return $elements;
    }
}

?>