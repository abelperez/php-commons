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
 * Chain contains a list of commands that are executed in the order that 
 * they are added to this chain.  The execution of commands in the chain is 
 * controlled by the response of each chain.  If any command in the chain 
 * returns true as the result of it's execution, then the chain stops and moves
 * on to the execution of filters in the chain.  
 *
 * Filters in the chain are special commands that that support post command 
 * processing. An example would be a filtering command that cleans up resources 
 * used by normal commands in the chain, like database connections etc.
 *
 * @package mindplex-commons-chain
 * @author @author Abel Perez 
 */
interface Chain
{
    /**
     * Adds the specified command to this chain.
     *
     * @param Command $command the command to add to this chain. 
     */
    public function addCommand(Command $command);
	
    /**
     * Executes this chain of commands with the specified context.
     *
     * @param Context $context the context for this command.
     */	 
    public function execute(Context &$context);
}

?>