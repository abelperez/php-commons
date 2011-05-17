<?php if (! defined('ACCESS_CONTROL')) exit('Access Denied!'); 

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
 * Session helper that wraps most of the common session operations into a simplier to use 
 * and less verbose API.
 *
 * @package mindplex-commons-web
 * @author Abel Perez
 */
class Session
{
	/**
	 * Gets the current session id.
	 */
	public static function getId() {
		Session::start();
		return session_id();
	}
	
	/**
	 * Gets the current session name.
	 */
	public static function getName() {
		Session::start();
		return session_name();
	}
		
	/**
	 * Starts the session if it's not already set.
	 */	
	public static function start() {
		if (! isset($_SESSION)) { 
			session_start(); 
		}
	}

	/**
	 * Stores the specified value under the given key 
	 * in the session store.
	 *
	 * @param string the key that identifies the specified
	 * value in the session store.
	 * @param value the value to store in the session store. 
	 */
	public static function put($key, $value) {
		Session::start();
		if (isset($key) && isset($value)) {
			$_SESSION[$key] = $value;
		}
	}

	/**
	 * Get's the value from the session store that 
	 * matches the specified key.
	 *
	 * @param string the key to lookup in the sesssion store.
	 * @return the value that maps to the specified key in the
	 * sessiion store.
	 */
	public static function get($key) {
		Session::start();
		if (isset($key) && isset($_SESSION[$key])) {
			return $_SESSION[$key];
		}
		return null;
	}
	
	/**
	 * Remove's the value from the session store that 
	 * is associated with the specified key.
	 */
	public static function remove($key) {
		Session::start();
		if (isset($key) && isset($_SESSION[$key])) {
			unset($_SESSION[$key]);
		}
		return null;
	}

	/**
	 * Destroy's the current session.
	 */
	public static function destroy() {
		Session::start();
		$_SESSION = array();
		session_unset();
		session_destroy();
	}
}

?>