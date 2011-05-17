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
 * Context is a key value map that serves the purpose of a shared context 
 * object between executing commands in a chain.
 *
 * @package mindplex-commons-chain
 * @author Abel Perez
 */
class Context extends ArrayMultimap
{
    /**
     * Get's the first value found that maps to the specified key.
     *
     * @param string $key the key to look up in this context.
     *
     * @return the first value found that maps to the specified key.
     */		
    public function getFirstValue($key) {
        if ($this->containsKey($key)) {
            $elements = $this->get($key);
            foreach ($elements as $element) {
                return $element;
            }
        }
    }
}

?>