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
 * Lexicon
 *
 * @package mindplex-commons-lang
 * @author Abel Perez
 */
class QueryLexicon
{
    /**
     *
     */
    private $symbols = array('#{', '}');

    /**
     * Default lexicon.
     */
    private $lexicon = array('#{AID}', '#{CID}', '#{START}', '#{END}', '#{COST}', '#{DISPOSITION}', '#{PHONE}');

    /**
     *
     */
    public function getSymbols() {
        return $this->symbols;
    }

    /**
     * Gets the configured lexicon for this
     * analyzer.
     */
    public function getLexicon() {
        return $this->lexicon;
    }
}

?>
