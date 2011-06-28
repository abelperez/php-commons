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
 * A weighted round robin array for efficient load balancing of elements
 * contained in this WeightedRoundRobinArray.
 *
 * @package mindplex-commons-collections
 * @author Abel Perez
 */
class WeightedRoundRobinList implements Iterator, Countable
{
    /**
     * The backing array of elements contained in this WeightedRoundRobinList.
     */
    private $elements;

	/**
	 * The size of this list.
	 */
	private $size = 0;

    /**
     * The position this list is currently in.  Each time this list is
     * accessed via the {@code get} method the position is tracked.
     * When this list has provided a complete distribution of its weighted
     * elements, the position is reset.
     *
     * Invocations of the {@code next} method of this lists Iterator,
     * also cause the position to adjust.
     */
    private $position = 0;

    /**
     * The modification count. Each time this list is modified, modCount
     * is incremented.  This helps iterators of this list detect concurrent
     * modifications.
     */
    private $modCount = 0;

	/**
	 * Constructs an empty WeightedRoundRobinList.
	 */
	public function WeightedRoundRobinArray() {
		$this->elements = array();
	}

	/**
	 * Adds the specified value and it's corresponding weight to this list. 
	 * A weight with a value equal to or less than zero will prevent the 
	 * specified value from being added to this list.  Valid weight values 
	 * must be greater than zero.
	 *
	 * If the specified value and weight combination exist in this list 
	 * then only the weight for the given value is updated; otherwise the 
	 * value weight combination is added to this list.
	 *
	 * @param $value mixed the value to add to this list.
	 * @param $weight int the weight to apply to the specified value.
	 *
	 * @return LoadBalanceList this list.
	 */
	public function add($value, $weight) {
	
		// if the specified weight is less than zero
		// there's no need to proceed.  Valid weight
		// values must be greater than zero.
		if ($weight < 0) return $this;
	
		// In order to verify if the specified value
		// and weight combination already exist in this
		// list, we create an element node that we can
		// use to search this list.
		$element = new Element($value, $weight);
	
		$index = $this->search($element);
		
		// if this list does not contain the given value
		// weight combination we add it to this list;
		// otherwise we update the existing weight of the
		// specified value with the given weight.
	
		if ($index < 0) {
			$this->elements[] = $element;
	
		} else {
			$target = $this->elements[$index];
			$target->setWeight($weight);
	
			// if the specified weight is smaller than the
			// values distribution count, we reset the count.
			// In other words this means that the value
			// has been accessed more times than the new
			// weight allows.
			if ($target->getCount() > $target.getWeight()) {
				$target->setCount(0);
			}
		}
	
		$this->modCount++;
		return $this;
	}

    /**
     * Sets the specified {@code value} and it's corresponding {@code weight}
     * to this list. A weight with a value equal to or less than zero will
     * prevent the specified value from being added to this list.  Valid weight
     * values must be greater than zero.
     *
     * <p>If the specified {@code value} and {@code weight} combination exist
     * in this list then only the weight for the given value is updated;
     * otherwise the value weight combination is added to this list.
     *
     * @param $value mixed the value to add to this list.
     * @param $weight int the weight to apply to the specified value.
     *
     * @return LoadBalanceList this list.
     */
	public function set($value, $weight) {
		return $this->add($value, $weight);
	}

    /**
     * Removes the specified element from this list. If this list does
     * not contain the specified element, then no action is taken and this
     * method returns false; otherwise true.
     *
     * @param $element Element the element to remove from this list.
     *
     * @return boolean true if this WeightedRoundRobinList is modified, 
	 * false otherwise.
     */
	public function remove($element) {
		$target = new Element($element, 0);
		
		$changed = FALSE;
		foreach ($this->elements as $k => $v) {
			if ($element == $v->getValue()) {
				unset($elements[$k]);
				$changed = TRUE;
				$this->size--;
			}
		}
	
		if ($changed) $this->modCount++;
		return $changed;
	}

    /**
     * Removes the specified collection of elements from this list. If this
     * list does not contain any of the specified elements, then no action is
     * taken and this method returns false; otherwise true.
     *
     * @param $collection array the collection of elements to remove from this list.
     *
     * @return {@code true} if this WeightedRoundRobinList is modified;
     * otherwise false.
     */
	public function removeAll($collection) {
		foreach ($collection as $element) {
			if (! $this->remove($element)) {
				return FALSE;
			}
		}
		return TRUE;
	}

    /**
     * Removes all the elements in this list that are not contained in the
     * specified collection.  Once this method is complete, this list will 
	 * only contain the elements found in the specified collection.
     *
     * @param $collection array the collection of elements to retain in this list.
     *
     * @return boolean true if this WeightedRoundRobinList is modified;
     * otherwise false.
     */
	public function retainAll($collection) {
	
		$changed = FALSE;
	
		foreach ($elements as $element) {
			
			// if the current element is not contained in
			// the specified collection, we remove it from
			// this list; otherwise we retain it.
			if (! in_array($element->getValue(),  $collection)) {
				$this->remove($element->getValue());
				$changed = TRUE;
			}
		}
	
		if ($changed) $this->modCount++;
		return $changed;
	}

    /**
     * Gets the next available item in this list. The item provided is
     * determined by its weight.  This list will provide elements in a
     * weighted round robin fashion.  Distributing the elements of this
     * list in weighted round robin fashion allows for efficiency in
     * load balancing the distribution of elements in this list.
     *
     * For example, the following code illustrates how this list effectively
     * load balances the distribution of elements contained in this list.
     *
     * <pre>
     * LoadBalanceList<String> list = new WeightedRoundRobinList<String>();
     * list.add("low", 1);
     * list.add("mid", 2);
     * list.add("high", 3);
     *
     * for (String element : list) {
     *     System.out.println(item + " ");
     * }
     * </pre>
     *
     * will produce the following output:
     * low mid high mid high high
     *
     * As you can see the code above yields each element the number
     * of times defined by its weight for each complete iteration of this
     * list.
     *
     * Its important to note that each call to this lists iterator
     * method, resets the current position of this list. Calling get()
     * several times, then calling the {@code iterator} method, resets this
     * list to a state that is equal to this lists {@code get} method never
     * being called.
     *
     * @return mixed the next element in this list according to the weighted
	 * round-robin policy.
     */
	public function get() {
	
		if (sizeof($this->elements) == 0) return NULL;
	
		if ($this->isDistributionComplete())  {
			$this->resetDistributionCounts();
			$this->position = 0;
		}
	
		$found = FALSE;
		while (! $found) {
	
			// if the current position exceeds the
			// size of the elements, then we reset the current
			// position to zero.  This effectively means that we
			// have reached the end of the list, as a result of
			// calling get.
			if ($this->position >= sizeof($this->elements)) {
				$this->position = 0;
			}
	
			$node = $this->elements[$this->position];
	
			// if the distribution count of the current
			// element is less than it's weight, then we
			// know that this element can be accessed.
			// We increment the elements distribution
			// count and break out of the loop.
	
			if ($node->getCount() < $node->getWeight()) {
				$node->incrementCount();
				$found = TRUE;
	
			} else {
				// this element is not the one we want
				// so we move the position forward and
				// continue through the loop.
				$this->position++;
			}
		}
	
		// get the element at the current position and
		// increment the modified count.
		$result = $this->elements[$this->position++];
		return $result->getValue();
	}

    /**
     * Returns true if the specified element is contained within this
     * list; otherwise false.
     *
     * @param mixed element the element to search for in this list.
     *
     * @return true if the specified element is contained within this
     * list; otherwise false.
     */
	public function contains($element) {
		$target = new Element($element, 0);
		return in_array($target, $this->elements);
	}

    /**
     * Returns true if the specified collection of element is contained
     * within this list; otherwise false.
     *
     * @param array collection the collection of elements to search for in this list.
     *
     * @return true if the specified collection of elements is contained
     * within this list; otherwise false.
     */
	public function containsAll($collection) {
		foreach ($collection as $element) {
	
			// no need to continue if at least
			// one element from the specified
			// collection is not contained in
			// this list.
			if (! $this->contains($element)) {
				return FALSE;
			}
		}
		return TRUE;
	}

    /**
     * Returns true if this WeightedRoundRobinList contains elements; 
	 * otherwise false.
     *
     * @return boolean true if this WeightedRoundRobinList contains
     * elements; otherwise false.
     */
	public function isEmpty() {
		return empty($this->elements);
	}

	/**
	 * Returns the count of elements contained in this list.
	 *
	 * @return the count of elements contained in this list.
	 */
	public function size() {
		return $this->size;
	}

	/**
	 * Removes all the elements contained in this WeightedRoundRobinList.
	 */
	public function clear() {
		unset($this->elements);
		$this->elements = array();
		$this->size = 0;
		$this->position = 0;
	}

    /**
     * Gets the list of elements contained in this WeightedRoundRobinList.
     *
     * @return array the list of elements contained in this WeightedRoundRobinList.
     */
	public function elements() {
		$copy = $this->elements;
		return $copy;
	}

    /**
     * This WeightedRoundRobinList concludes equality with the given
     * object by comparing each element in the specified list. The order of
     * elements also determines if the given object is equal to this list.
     * Lastly, the specified object's size must match this lists size in order
     * for both objects to be equal.
     *
     * @param $other WeightedRoundRobinList the other list to compare to this 
	 * list for equality.
     *
     * @return true if the specified object is equal to this list.
     */
    public function equals($other) {

        if ($this == $other) {
            return TRUE;
        }

        if ($other instanceof LoadBalancedList) {

            $otherList = $other;

            // the size of the specified object must match
            // the size of this list.
            if ($otherList->size() != $this->size()) {
                return FALSE;
            }

            // the order of the elements in the specified
            // object must match the order of elements in
            // this list.
            for ($i = 0; $i < sizeof($this->elements); $i++) {
                $e1 = $this->elements[$i];
                $e2 = $otherList->elements[$i];

                if (! ($e1 == NULL ? $e2 == NULL : $e1->equals($e2))) {
                    return FALSE;
                }
            }

            return TRUE;
        }

        return TRUE;
    }

    /**
     * Gets the hash code for this list.  Each element in this list is
     * used to conclude the final hash code for this list.
     *
     * @return this lists hash code.
     */
    public function hashCode() {
        $hash = 1;

        foreach ($elements as $element) {
           $hash = (31 * hash) + ($element == NULL ? 0 : $element->hashCode());
        }

        return $hash;
    }
	
    /**
     * Loads the specified list of elements into this WeightedRoundRobinList.  
	 * Any existing elements in this list are removed before loading the 
	 * specified list.  In other words this method re-initializes this list.
     *
     * @param $elements array the list of elements to reinitialize this list with.
	 * @param $weight int the default weight to set on the elements of the specified
	 * list of elements.
	 */
    protected function initialize($elements, $weight = 1) {

        // this method is basically a way to
        // reinitialize this list, so we clear
        // any existing elements in this list.
        $this->clear();

        foreach ($elements as $element) {
            $this->add($element, $weight);
        }
    }
	
    /**
     * Returns true if every element in this list has been
     * distributed according to its weight.  For example, this method
     * return tue if a list with an element A that has a weight of 1
     * and a second element B with a weight of 2 has been accessed in
     * the following order: A, B, B.
     *
     * @return true if every element in this list has been
     * distributed according to its weight.
     */
	public function isDistributionComplete() {
		$complete = TRUE;
	
		foreach ($this->elements as $element) {
			if ($element->getCount() < $element->getWeight()) {
				$complete = FALSE;
			}
		}
		return $complete;
	}

    /**
     * Resets the distribution count for each element in this list to 0.
     */
	public function resetDistributionCounts() {
		foreach ($this->elements as $element) {
			$element->setCount(0);
		}
	
		// Since we are resetting the distribution count,
		// we also reset the modification count.
		$this->modCount = 0;
	}

	/*
	 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	  Iterable Operations
	 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	 */
	
	/**
	 * The element in this Iterators current position.
	 */
	private $current;
	
	/**
	 * Flag that denotes if this Iterator is in a valid state.  In this
	 * context, a valid state, referrs to this iterators ability to move 
	 * forward.
	 */
	private $valid = TRUE;
	
	/**
	 * Rewind the Iterator to the first element. Similar to the reset() 
	 * function for arrays in PHP 
	 *
	 * @return void
	 */
	public function rewind() {
		$this->resetDistributionCounts();
		$this->current = $this->get();
		$this->valid = TRUE;
	}

	/**
	 * Return the current element. Similar to the current() function for 
	 * arrays in PHP.
	 *
	 * @return mixed current element from the collection 
	 */
	public function current() {
		return $this->current;
	}

	/**
	 * Return the identifying key of the current element. Similar to the key() 
	 * function for arrays in PHP.
	 *
	 * @return mixed either an integer or a string 
	 */
	public function key() {
		return $this->position();
	}
	
	/**
	 *
	 * @return
	 */
	public function next() {
		
		// blow up with a no such element exception
		// because we have completely iterated over
		// all the elements in the list this iterator
		// represents.
		if ($this->isDistributionComplete()) {
			$this->valid = FALSE;
			return;
		}

		try {
			$this->current = $this->get();
			
		} catch (Exception $exception) {
			$this->valid = FALSE;
		}
	}
	
	/**
	 *
	 * @return
	 */
	public function valid() {
		return $this->valid;
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
		return sizeof($this->elements);
	}

	protected function position() {
		return $this->position;	
	}	
	
	/**
	 * Search this list for the specified element and return its index in
	 * the internal array that backs this list.
	 *
	 * @param $element Element the element to search for.
	 *
	 * @return int the array index for the element found as a result of this
	 * search.
	 */
	private function search($element) {
		for ($i = 0; $i < sizeof($this->elements); $i++) {
			$target = $this->elements[$i];
			if ($element->getValue() == $target->getValue()) {
				return $i;		
			}
		}
		return -1;
	}
}

/**
 * A Element is a container object that contains a value with a weight 
 * and a counter.
 * 
 * @author Abel Perez
 */
class Element
{
    /**
     * The value this element contains.
     */
    private $value;

    /**
     * The weight of the value this element contains.
     */
    private $weight;

    /**
     * A generic counter that can serve multiple purposes for example,
     * in a {@code List} of weighted elements, this counter can be used
     * to track the distribution of the element.
     */
    private $count = 0;

    /**
     * Constructs this element with the supplied value and weight.
	 *
     * @param $value mixed the underlying value for this element.
     * @param $weight int the weight of the underlying value this element contains.
     * 
     * @return Element an Element from the supplied value and weight.
     *
     * @throws Exception if the specified value is null.	 
	 */
    public function Element($value, $weight) {
		if ($value == NULL) throw new Exception('specified value is null.');
        $this->value = $value;
        $this->weight = $weight;
    }

    /**
     * Returns an Element from the supplied value and weight.
     * 
     * @param $value mixed the underlying value for this element.
     * @param $weight int the weight of the underlying value this element contains.
     * 
     * @return Element an Element from the supplied value and weight.
     *
     * @throws Exception if the specified value is null.
     */
    public static function of($value, $weight = 1) {
		return new Element($value, $weight);
    }

    /**
     * Gets the value this element contains.
     * 
     * @return mixed the value this element contains.
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Sets the value this element contains.
     * 
     * @param $value mixed the value this element contains.
     *
     * @throws Exception if the specified value is null.
     */
    public function setValue($value) {
		if ($value == NULL) throw new Exception('specified value is null.');
        $this->value = $value;
    }

    /**
     * Gets the weight of the value this element contains.
     *
     * @return int the weight of the value this element contains.
     */
    public function getWeight() {
        return $this->weight;
    }

    /**
     * Sets the weight of the value this element contains.
     *
     * @param $weight int the weight of the value this element contains.
     */
    public function setWeight($weight) {
        $this->weight = $weight;
    }

    /**
     * Gets this elements count.
     * 
     * @return int this elements count.
     */
    public function getCount() {
        return $this->count;
    }

    /**
     * Sets this elements counter to the specified count.
     *
     * @param $count int the count to update this elements count with.
     */
    public function setCount($count) {
        $this->count = $count;
    }

    /**
     * Increments this elements counter by 1.
     * 
     * @return int the new count.
     */
    public function incrementCount() {
        $this->count = $this->count + 1;
		return $this->count;
    }

    /**
     * Decrements this elements counter by 1.
     *
     * @return int the new count.
     */
    public function decrementCount() {
        $this->count = $this->count - 1;
		return $this->count;
    }

    /**
     * Equality is ultimately determined by comparing the underlying value
     * contained in this element.
     *
     * @param $other Element the other element to compare against this one.
     *
     * @return true if the specified element is equal to this one;
     * otherwise false.
     */
	public function equals($other) {
        if (! ($other instanceof Element)) {
        	return FALSE;
        }
        return ($this->value == $other->value);
    }
	
	/**
	 * Gets the hash code of this element.  The hashcode serves the purpose of 
	 * a unique id for an instance of this object.
	 *
	 * @return int this objects hashcode.
	 */
	public function hashCode() {
		$hash = 7;
        $hash = 97 * $hash + ($this->value != NULL ? spl_object_hash($this) : 0);
        return $hash;	
	}
}

?>