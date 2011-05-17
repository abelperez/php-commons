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
 * CommandSequence contains a sequence of commands that can be executed by a 
 * chain of responsibility.
 *
 * @see Chain, Command
 *
 * @package mindplex-commons-chain
 * @author Abel Perez
 */
class CommandSequence
{
    /** map of commands */
    private $commands;
	
    /**
     * Constructs this CommandSequence
     */
    public function CommandSequence() {
        $this->command = array();	
    }

    /**
     * Add's the specified command to this sequence.
     *
     * @param string $name the name of the command to add to this sequence.
     * @param Command $command the command to add to this sequence.
     */
    public function addCommand($name, Command $command) {
        if (! isset($name) OR $name === '' OR 
            ! isset($command)) return;
			
        $this->commands[$name] = $command;	
    }
	
    /**
     * Add's the specified map of commands to this sequence.
     *
     * @param array $commands the map of commands to add to this sequence.
     */
    public function addCommands($commands) {
        if (! is_array($commands)) return;
		
        $changed = FALSE;
        foreach ($commands as $k => $v) {	
            $this->commands[$k] = $v;
            $changed = TRUE;
        }
        return $changed;	
    }	
	
    /**
     * Get's the command based on the specified command name; otherwise returns 
     * NULL.
     *
     * @param string $name the name of the command to get from this sequence.
     *
     * @return the command based on the specified command name; otherwise returns 
     * NULL.
     */	
    public function getCommand($name) {
        if (! isset($name)) return NULL;
        if (! in_array($name)) return NULL;
		
        return $this->commands[$name];
    }
	
    /**
     * Get's the list of commands in this sequence; otherwise returns an empty 
     * array.
     *
     * @return the the list of commands in this sequence; otherwise returns an 
     * empty array.
     */	
    public function getCommands() {
        $commands = array();
        foreach ($this->commands as $command) {
            $commands[] = $command;
        }
        return $commands;
    }	
	
    /**
     * Get's the list of command names set in this sequence; otherwise returns 
     * an empty array.
     *
     * @return the list of commands set in this sequence; otherwise returns an 
     * empty array.
     */	
    public function getNames() {
        return array_keys($this->commands);	
    }
	
    /**
     * Removes all the commands in this sequence.
     */	
    public function clear() {
        unset($this->commands);
        $this->commands = array();
    }
}

?>