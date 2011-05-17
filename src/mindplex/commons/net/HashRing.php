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
 * HashRing is a simple implementation of consistent hashing.
 *
 * Example usage:
 *
 * <code>
 * <?php 
 *
 * // create a new ring with replica count of 100
 * $ring = new HashRing(100);
 *
 * // add server nodes to ring
 * $ring->addNodes(array("1.2.3.4:11211", "5.6.7.8:11211", "9.8.7.6:11211"));
 *
 * // get server node location for the specified resource
 * $node = $ring->getNode("info that I need to store in a distributed cache");
 *
 * echo "found resource in node: $node";
 *
 * ?>
 * </code>
 *
 * This example prints: "found resource in node: 9.8.7.6:11211" as you can
 * see the resource was found in the node "1.2.3.4:11211."
 *
 * @package mindplex-commons-net
 * @author Abel Perez 
 */
class HashRing
{
    /**
     * The number of virtual nodes per physical node.
     */
    private $replicaCount;

    /**
     * The ring of evenly distributed nodes in the hash space.
     */
    private $ring = array();

    /**
     * Constructs this HashRing with the specified replica count.
     *
     * @param int $replicaCount the count of replicas (virtual nodes) per physical 
     * node added to this HashRing
     */
    public function HashRing($replicaCount = 6) {
        $this->replicaCount = $replicaCount;
    }

    /**
     * Add's the specified nodes plus the number of virtual nodes defined by the 
     * replica count to this HashRing.
     *
     * @param array $nodes the nodes to add to this HashRing
     */
    public function addNodes($nodes) {
        foreach ($nodes as $node) {
            $this->addNode($node);
        }
    }

    /**
     * Add's the specified node plus the number of virtual nodes defined by the 
     * replica count to this HashRing.
     *
     * @param string $node the node to add to this HashRing
     */
    public function addNode($node) {
        for ($i = 0; $i < $this->replicaCount; $i++) {
            $key = $this->hash($node.":".$i);
            $this->ring[$key] = $node;
        }
        ksort($this->ring);
    }

    /**
     * Get's the node that contains the specified element.
     *
     * @param string $element the lookup element for retreiving it's storage node
     *
     * @return string the node that contains the specified element
     */
    public function getNode($element) {
        $hash = $this->hash($element);
        if (array_key_exists($hash, $this->ring)) {
            return $this->ring[$hash];
        }
        foreach (array_keys($this->ring) as $key) {
            if ($key > $hash) {
                return $this->ring[$key];
            }
        }
        return $this->ring[0];
    }

    /**
     * Hashes the specified element.
     *
     * @param string $element the element to hash
     *
     * @return int the hash of the specified element
     */
    public function hash($element) {
        return crc32($element);
    }

    /**
     * String representation of this HashRing.
     *
     * @return string flattens out this HashRing into a string representation
     */
    public function toString() {
        $r = 'HashRing = [';
        foreach ($this->ring as $k => $v) {
            $r .= '{"'.$k.'":"'.$v.'"},';
        }
        return rtrim($r, ',').']';
    }
}

?>