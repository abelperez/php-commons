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
 * SyntaxTokenizer
 *
 * @package mindplex-commons-lang
 * @author Abel Perez
 */
class LexicalAnalyzer
{
    /**
     * Default lexicon.
     */
    private $lexicon;

    /**
     * Constructs this lexical analyzer with the specified lexicon.
     */
    public function LexicalAnalyzer($lexicon = null) {
        if ($lexicon != null) {
            $this->lexicon = $lexicon;

        } else {
            $this->lexicon = new QueryLexicon();
        }
    }

    /**
     * Gets the configured lexicon for this analyzer.
     */
    public function getLexicon() {
        return $this->lexicon;
    }

    /**
     * Analyzes the specified expression.
     */
    public function analyze($expression) {

        $output = array();
        foreach ($this->lexicon->getLexicon() as $token) {
            $pos = strpos($expression, $token);

            if ($pos === false) {

            } else {
                $output[] = $token;
            }
        }

        return $output;
    }
}

?>