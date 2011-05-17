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
 
if (! defined('__DEFAULT_VIEW__')) {
	define('__DEFAULT_VIEW__', 'view');
}
if (! defined('__DEFAULT_ACTION__')) {
	define('__DEFAULT_ACTION__', 'index.php?Action=view');
}
if (! defined('__MISSING_ACTION__')) {
	define('__MISSING_ACTION__', 'missing_action');
}

/**
 * GenericController contains methods that are commonly found and support the 
 * (MVC) FrontController 
 * pattern.
 *
 * @package mindplex-commons-web
 * @author Abel Perez
 */
abstract class GenericController 
{
	/** flag that denotes if a redirect has been performed. */
	private $performed = false;
	
	/*
	 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	  Redirect Operations
	 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	*/
		
	/**
	 * Redirects to the specified target url.  In most cases this will be a 
	 * controller action or specific view.
	 */
	public function redirect($url) {
		if (headers_sent() OR $this->performed == TRUE) {
			throw new Exception(
				"Cannot modify header information - headers already sent.");
		}
		$this->performed = TRUE;
		header("Location: ".$url);
		exit();
	}
	
	/**
	 * Redirects to the default error page for this controller.  In most cases
	 * this method will be invoked as the result of a request for an unknown
	 * resource (controller action).
	 */	
	protected function error($action = __MISSING_ACTION__) {
		header("Location: /error.php?Action=".$action);	
	}
	
	/*
	 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	  Validation Operations
	 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	*/
		
	/**
	 * Validates that the current HTTP request contains a valid action.
	 *
	 * @return true if the current HTTP request contains a valid action; 
	 * otherwise false.
	 */		
	protected function validateAction() {
		if (isset($_POST['Action']) || isset($_POST['action'])) {
			if (isset($_POST['Action'])) {
				$action = htmlspecialchars($_POST['Action']);
			} else {
				$action = htmlspecialchars($_POST['action']);
			}	
			return strtolower($action);
			
		} else if (isset($_GET['Action']) || isset($_GET['action'])) {
			if (isset($_GET['Action'])) {
				$action = htmlspecialchars($_GET['Action']);	
			} else {
				$action = htmlspecialchars($_GET['action']);
			}
			return strtolower($action);
			
		} else {
			if (headers_sent()) {
				return $this->getDefaultActionAfterHeaders();
			} else {
				header("Location: ".$this->getDefaultAction());
				exit();
			}
		}
	}
	
	/**
	 * Validates the current request against the specified parameter list.  
	 * If any of the specified parameters are found to be null or empty in 
	 * the current request the validation will fail and the request should be 
	 * marked invalid.
	 *
	 * @param array $attributes list of attributes to validate.
	 * @param boolean <code>$failfast</code> true if this validation should 
	 * fail fast (return on first encounter of invalid attribute).
	 *
	 * @return true if the current request validates; otherwise false.
	 */
	protected function validate($parameters, $failfast = false) {
		$result = new Validation();
		if (! is_array($parameters)) {
			return $result;
		}
		
		$result->setValid(true);
		foreach ($parameters as $parameter) {
			$value = $this->get($parameter);
			if ($value === NULL OR $value === '' OR empty($value)) {
				$result->setValid(false);
				$result->addError($parameter);
				if ($failfast) return $result; 
			}
		}
		return $result;
	}
	
	/*
	 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	  Request Operations
	 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	*/
					
	/**
	 * Get's the value of the specified HTTP parameter name.  
	 *
	 * @name string HTTP request parameter name.
	 * @value string the default value in case the specified HTTP parameter does 
	 * not exist.
	 *
	 * @return the value of the specified HTTP request parameter.
	 */
	protected function get($name, $value = NULL) {
		if (isset($_GET[$name])) {
			return trim(htmlspecialchars($_GET[$name]));	
		} else if (isset($_POST[$name])) {
			return trim(htmlspecialchars($_POST[$name]));
		}
		return $value;
	}
	
	/*
	 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	  Routing Operations
	 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	*/
	
	/**
	 * route request to target controller method.
	 */
	public function route() {
		$action = $this->validateAction();
		if (method_exists($this, str_replace('-', '', $action))) {
			$this->$action();
		} else {
			$this->error($action);
		}
	}
	
	/**
	 * Routes the current HTTP request to the specified controller action without 
	 * the need to validate the action (access control).
	 *
	 * @param string $action the action to route.
	 *
	 * @return the result of invoking the specified action.
	 */
	protected function fastRoute($action) {
		if (method_exists($this, str_replace('-', '', $action))) {
			return $this->$action();
		} else {
			return $this->error($action);
		}
	}
	
	/*
	 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	  Binding Operations
	 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	*/
		
	/**
	 * Binds the current HTTP request to the specified active record.
	 *
	 * @param model $model the model to bind to the current HTTP request.
	 *
	 * @return model bound to the current HTTP request.
	 */
	protected function bind(&$model) {
		foreach ($model->getAttributeNames() as $attribute) {
			// dont bind Created date attribute (default is now).
			if ($attribute === 'created') continue; 
			$model->$attribute = $this->get($attribute);
		}
		return $model;
	}
	
	/**
	 * Binds the current request to the specified active record. If the HTTP 
	 * request contains an identity value (id) the identity attribute will not
	 * be bound in order to preserve the identity of the specified model.
	 *
	 * @param model $model the model to bind to the current HTTP request.
	 *
	 * @return model bound to the current HTTP request.
	 */
	protected function bindExisting(&$model) {
		foreach ($model->getAttributeNames() as $attribute) {
			// ignore id and created
			if ($attribute == 'id') continue;
			if ($attribute == 'created') continue; 
			$model->$attribute = $this->get($attribute);
		}
		return $model;
	}
	
	/**
	 * Binds and saves the current HTTP request to the specified active record.
	 *
	 * @param model $model the model to bind to the current HTTP request and save.
	 *
	 * @return model bound to the current HTTP request.
	 */	
	protected function bindAndSave(&$model) {
		$model = $this->bind($model);
		$model->saveOrUpdate();
		return $model;
	}	
	
	/*
	 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	  Support Operations
	 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	*/
		
	/**
	 * Get's the current date in military format.
	 *
	 * @access public
	 * @return date	the current date in military format.
	 */
	protected function getSystemDate() {
		return date('Y-m-d H:i:s');
	}
	
	/**
	 * Get's the current date in 12 hour format.
	 *
	 * @access public
	 * @return date	the current date in 12 hour format.
	 */
	protected function getDate() {
		return date('Y-m-d h:i:s');
	}

	/**
	 * Get's the default HOME action for this controller. If no default HOME action 
	 * has been specified by the calling subclass of this controller, the default 
	 * action will be defined as the following: 
	 * index.php?Action=view
	 *
	 * @return string default HOME action for this controller.
	 */	
	public function getHome() {
		if (! defined('__HOME__')) {
			define('__HOME__', 'index.php?Action=view');
		}	
		return __HOME__;
	}
	
	/**
	 * Get's the remote host ip address.
	 *
	 * @return the remote host ip address.
	 */
	public function getIp() {
		return $_SERVER['REMOTE_ADDR'];	
	}
	
	/**
	 * Get's the default action for this controller. If no default action has
	 * been specified by the calling subclass of this controller, the default
	 * action will be defined as the following:
	 * index.php?Action=view
	 *
	 * @return string default action for this controller.
	 */ 
	protected function getDefaultAction() {	
		return __DEFAULT_ACTION__;
	}
	
	/**
	 * Get's the default "view" action, when no Action parameter is specified
	 * in the request.
	 *
	 * @return the default "view" action, when no Action parameter is specified
	 * in the request.
	 */
	protected function getDefaultActionAfterHeaders() {
		return __DEFAULT_VIEW__;
	}
	
	/**
	 * Check if this controller is alive.
	 */
	protected function heartbeat() {
		echo '{"status":"alive"}';   
	}		
}

/**
 * Validation object that contains validation errors if any and the final 
 * validation disposition.
 */
class Validation 
{
	/** */
	private $valid = false;
	
	/** */
	private $errors = array();
	
	/**
	 *
	 */	
	public function Validation() {
	}
	
	/**
	 *
	 */
	public function setValid($valid) {
		$this->valid = $valid;
	}

	/**
	 *
	 */	
	public function addError($attribute) {
		$this->errors[] = $attribute;
	}
	
	/**
	 *
	 */	
	public function valid() {
		return $this->valid;
	}
	
	/**
	 *
	 */	
	public function getErrors() {
		return $this->errors;
	}
}

?>