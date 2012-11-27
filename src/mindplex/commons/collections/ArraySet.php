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
        $result = !$this->contains($element);
        $this->elements[$element] = true;
        return $result;
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
        $newcollection = array_fill_keys($collection, true);
        $origlength = $this->size();
        $this->elements = $this->elements + $newcollection;
        return !($origlength == $this->size());
    }

    /**
     * Removes all the elements from this set.
     */
    public function clear() {
        $this->elements = array();
    }

    /**
     * Checks if this set contains the specified element. 
     *
     * @param any $element
     *
     * @returns true if this set contains the specified element.
     */
    public function contains($element) {
        return array_key_exists($element, $this->elements);
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
            if (! $this->contains($element)) {
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
        return $this->size() == 0;
    }

    /**
     * Get's an iterator over the elements in this set.
     *
     * @returns an iterator over the elements in this set.
     */
    public function iterator() {
        return new SimpleIterator(array_keys($this->elements));
    }

    /**
     * Removes the specified element from this set.
     *
     * @param any $element
     *
     * @returns true if the specified element is removed.
     */
    public function remove($element) {
        if (!$this->contains($element, $this->elements)) {
            return false;
        }

        unset($this->elements[$element]);
        return true;
    }

    /**
     * Removes all the specified elements from this set.
     *
     * @param array $collection
     *
     * @returns true if all the specified elemensts are removed from this set. 
     */
    public function removeAll($collection) {
        $origlength = $this->size();
        $this->elements = array_diff_key($this->elements, array_fill_keys($collection, true));
        return !($origlength == $this->size());
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
        $newcollection = array_fill_keys($collection, true);
        $origlength = $this->size(); 
        $this->elements = array_intersect_key($this->elements, $newcollection);
        return !($origlength == $this->size());
    }

    /**
     * Returns the number of elements in this set.
     *
     * @returns the number of elements in this set.
     */
    public function size() {
        return count($this->elements);
    }

    /**
     * Returns an array that contains all the elements in this set.
     *
     * @returns an array that contains all the elements in this set.
     */
    public function toArray() {
        return array_keys($this->elements);
    }
}

?>
