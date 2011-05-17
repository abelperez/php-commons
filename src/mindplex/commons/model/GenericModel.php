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
 * Generic Model with support for dynamic properties.
 *
 * @package mindplex-commons-model
 * @author Abel Perez
 */
abstract class GenericModel
{
    /** */
    private $id;

    /** */
    private $clazz;

    /** */
    private $fields = array();

    /**
     *
     */
    public function initialize($fields) {
        $this->fields = $fields;
        $this->clazz = '';
    }

    /**
     * This varient of initialization sets the class name of this model.
     */
    public  function initializeStatic($fields, $clazz) {
        $this->fields = $fields;
        $this->clazz = $clazz;
    }
    
    /**
     * property getter.
     */
    public function __get($field) {
        return $this->fields[$field];
    }
     
    /**
     *
     */ 
    public function getAttributes() {
        return $this->fields;
    }
        
    /**
     * property setter.
     */
    public function __set($field, $value) {
        if (array_key_exists($field, $this->fields)) {
            $this->fields[$field] = $value;    
        }
    }
    
    /**
     * Gets the class name of this model.
     */
    function getClass() {
        if ($this->clazz == '') {
            $this->clazz = get_class($this);
        }
        return $this->clazz;
    }
    
    /**
     * Get's the current date in 12 hour format.
     *
     * @access public
     * @return date the current date in 12 hour format.
     */
    public function getDate() {
        return date('Y-m-d h:i:s');
    }
        
    /**
     * Initializes this Patient attributes from the specified attribute array. 
     */
    private function __initAttributes($attributes) {
        if (is_array($attributes)) {
            $this->fields = $attributes;
        }
    }
    
    // Metadata Functions
                
    /**
     * Checks if this Entity has the specified property.
     */
    public function hasAttribute($attribute) {
        return array_key_exists($attribute, $this->fields);
    }
                        
    /**
     * Gets the attribute names for this Entity. 
     */
    public function getAttributeNames() {
        $result = array();
        foreach($this->fields as $key => $value) {
            $result[] = $key;
        }
        return $result;
    }
    
    /**
     * Trims the last character from the specified target. 
     */
    private function trimLastCharacter($target, $index = -1) {
        if (isset($target)) {
            return substr_replace($target ,"", $index);
            
        } else {
            return $target;
        }
    }
        
    /**
     * Checks if the specified attribute is set.
     */
    public function isAttributeSet($attribute) {
        if (array_key_exists($attribute, $this->fields)) {
            $value = $this->fields[$attribute];
            return ($value == null || $value == ''  || empty($value)) ? false : true;
        }
        return false;
    }

    /**
     * Merges the specified attributes with this models existing attributes.
     */
    public function merge($attributes) {
        $this->fields = array_merge($this->fields, $attributes);
        return $this;
    }
	
    /**
     * Renders this model in xml format.
     */
    public function xml($declaration = true) {
        $class = $this->getClass();
        $xml = '';  
        if ($declaration) {
            $xml .= '<?xml version="1.0" encoding="UTF-8"?>';
        }
        $xml .= '<'.$class.'>';

        foreach ($this->getAttributeNames() as $attribute) {
            $xml .= '<'.$attribute.'>'.$this->$attribute.'</'.$attribute.'>';
        }

        $xml .= '</'.$class.'>';
        return $xml;    
    }

    public function toString() {
        $result = '';

        foreach ($this->getAttributeNames() as $attribute) {
            $result .= $attribute.' = '.$this->$attribute.', ';
        }

        return $result;	
    }	
}

?>
