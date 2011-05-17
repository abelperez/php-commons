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
 * Set is a collection that contains no duplicate elements and at most one null element.  
 * This Set models after the mathametical Set abstraction.
 *
 * @package mindplex-commons-collections
 * @author Abel Perez 
 */
interface Set
{
    /**
     * Adds the specified element to this set if it is not already present.
     *
     * @param any $element
     *
     * @returns true if the specified element was added to this set.
     */
    public function add($element);

    /**
     * Adds all of the elements in the specified collection to this set if they're not 
     * already present.
     *
     * @param array $collection
     *
     * @returns true if any of the elements in the specified collection where added to 
     * this set. 
     */
    public function addAll($collection);

    /**
     * Removes all the elements from this set.
     */
    public function clear();

    /**
     * Checks if this set contains the specified element. 
     *
     * @param any $element
     *
     * @returns true if this set contains the specified element.
     */
    public function contains($element);

    /**
     * Checks if this set contains all the specified element.
     *
     * @param array $collection
     *
     * @returns true if this set contains all the specified element. 
     */
    public function containsAll($collection);

    /**
     * Checks if this set contains elements.
     *
     * @returns true if this set contains no elements. 
     */
    public function isEmpty();

    /**
     * Get's an iterator over the elements in this set.
     *
     * @returns an iterator over the elements in this set.
     */
    public function iterator();

    /**
     * Removes the specified element from this set.
     *
     * @param any $element
     *
     * @returns true if the specified element is removed.
     */
    public function remove($element);

    /**
     * Removes all the specified elements from this set.
     *
     * @param array $collection
     *
     * @returns true if all the specified elemensts are removed from this set. 
     */
    public function removeAll($collection);

    /**
     * Retains the elements in this set that are in the specified collection.  If the 
     * specified collection is also a set, this method effectively modifies this set 
     * into the intersection of this set and the specified collection.
     *
     * @param array $collection
     *
     * @returns true if this set changed as a result of the specified collection.
     */
    public function retainAll($collection);

    /**
     * Returns the number of elements in this set.
     *
     * @returns the number of elements in this set.
     */
    public function size();

    /**
     * Returns an array that contains all the elements in this set.
     *
     * @returns an array that contains all the elements in this set.
     */
    public function toArray();
}

?>