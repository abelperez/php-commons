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
 * ModelParser
 *
 * @package mindplex-commons-lang
 * @author Abel Perez
 */
class ModelParser
{
    /**
     *
     */
    private $analyzer;

    /**
     *
     */
    public function ModelParser($analyzer) {
        if ($analyzer == null) {
            $analyzer = new LexicalAnalyzer();
        }
        $this->analyzer = $analyzer;
    }

    /**
     *
     */
    public function parse($expression) {
        $output = $this->analyzer->analyze($expression);
        $attributes = array();

        foreach ($output as $token) {
            $result = str_replace($this->analyzer->getLexicon()->getSymbols(), '', $token);
            $attributes[strtolower($result)] = '';
        }

        return new DynamicModel($attributes, 'DynamicModel');
    }
}
?>