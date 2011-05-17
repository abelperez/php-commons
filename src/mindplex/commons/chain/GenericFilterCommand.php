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
 * GenericFilterCommand contains generic support methods for implementations of 
 * FilterCommand. 
 *
 * @package mindplex-commons-chain
 * @author Abel Perez 
 */
class GenericFilterCommand implements FilterCommand
{
    public function execute(Context &$context) {
        $context->put('log', 'skipping filter command');
        return false;
    }
	
    /**
     * @see FilterCommand::postprocess documentation.
     */
    public function postprocess(Context &$context, $exception) {
        $context->put('log', 'executing post processing');
        if (isset($exception)) $this->notify($context, $exception);
        return TRUE;
    }
	
    /**
     * Notifies that the specified exception has occurred.
     *
     * @param Context $context chain context.
     * @param Exception $exception the exception that occurred during the 
     * execution of the command chain.
     */	
    protected function notify(Context $context, $exception) {
        $context->put('log', 'sending error notification');
    }	
}

?>