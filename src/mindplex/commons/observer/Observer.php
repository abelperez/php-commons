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
 * Observer participates in the Observable pattern by registering for notification 
 * of changes in a monitored state object or subject.
 *
 * @see Subject
 *
 * @package mindplex-commons-observer
 * @author Abel Perez
 */ 
interface Observer
{
    /**
     * This is a callback method for Subject's that this observer has registered 
     * with.
     *
     * @state object $state the monitored state that changed.
     */
    public function notify($state);
}

?>