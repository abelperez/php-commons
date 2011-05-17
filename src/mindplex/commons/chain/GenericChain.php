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
 * GenericChain is a base class for Chain implementations.
 *
 * @package mindplex-commons-chain
 * @author Abel Perez
 */
class GenericChain implements Chain
{
    /** list of commands */
    private $commands;
	
    /**
     * Constructs this chain with the specified command or list of commands.
     *
     * @param mixed $commands command or list of commands.
     */
    public function GenericChain($commands) {
        if (is_array($commands)) {
            $this->commands = $commands;
        } else {
            $commands = array($commands);
        }
    }

    /**
     * @see Chain::addCommand documentation.
     */	 	
    public function addCommand(Command $command) {
        if (! isset($command)) return;
        $this->commands[] = $command;
    }
	 
    /**
     * @see Chain::execute documentation.
     */	 
    public function execute(Context &$context) {
		
        if (! isset($context)) {
            $context = new Context();
        }
				
        $result = FALSE;
        $error = NULL;
        foreach ($this->commands as $command) {
            try {
                $result = $command->execute($context);
                if ($result) {
                    break;
                }
            } catch (Exception $exception) {
                $error = $exception;
                break;
            }
        }
		
        $filters = $this->commands;
        rsort($filters);
		
        $processed = FALSE;
        foreach ($filters as $filter) {
            if ($filter instanceof FilterCommand) {
                $filterResult = $filter->postprocess($context, $error);
                if ($filterResult) $processed = TRUE;
            }
        }
		
        if (isset($error) && ! $processed) {
            throw $error;
        } else {
            return $result;
        }
    }
}

?>