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
 * FilterCommand is a post processing command that is invoked after all commands 
 * in a chain have completed executing or a specific command in the chain stops 
 * execution.  This is basically a hook into processing operations post the 
 * execution of a chain for example, cleaning up resources, triggering 
 * notifications, and logging chain execution results.  
 *
 * @package mindplex-commons-chain
 * @author Abel Perez 
 */
interface FilterCommand extends Command
{
    /**
     * This method performs post chain processing operations required e.g., 
     * cleaning up resources.
     *
     * @param Context $context chain context.
     * @param Exception $exception the exception that occurred during the 
     * execution of the command chain.
     *
     * @return true if post process is handled.
     */
    public function postprocess(Context &$context, $exception);
}

?>