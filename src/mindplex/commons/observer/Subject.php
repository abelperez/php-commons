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
 * Subject that observers register with for notification of changes in the current 
 * state contained in this subject.
 *
 * @see Observer
 *
 * @package mindplex-commons-observer
 * @author Abel Perez
 */ 
class Subject
{
    /** list of observers */
    private $observers;

    /** state to monitor for changes */
    private $state;

    /** 
     * Constructs this Subject with the specified state.
     *
     * @param object $state the state to monitor for changes.
     */
    public function Subject($state = NULL) {
        $this->observers = array();
        $this->state = $state;
    }

    /**
     * Get's the state this Subject is monitoring.
     *
     * @return the state this Subject is monitoring.
     */
    public function getState() {
        return $this->state;
    }

    /** 
     * Changes the state this Subject monitors to the specified state and notifies 
     * all registered Observers of the state change.
     *
     * @param object $state the state to change.
     */	
    public function changeState($state) {
        $this->state = $state;
        $this->notify();
    }

    /** 
     * Attaches the specified observer to this subject for notification of state 
     * changes.
     *
     * @param Observer $observer observer to register for notifications.
     *
     * @return true if the specified observer is attached to this subject.
     */	
    public function attach($observer) {
        if (! isset($observer)) return FALSE;
        $this->observers[] = $observer;
        return TRUE;	
    }

    /** 
     * Detaches the specified observer from the list of registered observers.
     *
     * @param Observer $observer observer to detach from the list of registered 
     * observers.
     *
     * @return true if the specified observer is detached from this subject.
     */	
    public function detach($observer) {
        if (! isset($observer)) return FALSE;
        foreach ($this->observers as $k => $v) {
            if ($observer === $v) {
                unset($this->observers[$k]);
            }
        }
        return TRUE;
    }

    /** 
     * Notifies all observers that have registyered with this subject that the 
     * state being monitored has changed.
     *
     * TODO: Add a way to give the option of whether to fail entirely if one
     * observer notification fails or just log and continue.  Maybe even set
     * a member variable that denotes an error was encountered with the 
     * exception i.e., an associated array with key as observer and value as
     * exception raised.
     *
     * @return true if all observers are notified; otherwise false if at least 
     * one observer fails.
     */	
    public function notify() {
        $success = TRUE;
        foreach ($this->observers as $observer) {
            try {
                $observer->notify($this->state);
            } catch (Exception $exception) {
                // see TODO in comments of this method
                $success = FALSE;
            }
        }
        return $success;
    }
}

?>