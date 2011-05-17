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
 
if (! defined('__DEFAULT_LOGIN_PAGE__')) {
  define('__DEFAULT_LOGIN_PAGE__', '/login.php');
}
if (! defined('__SITE__')) {
  define('__SITE__', 'none');
}

class Auth {
  const ACCESS   = 'A2';
  const USERID   = 'A3';
  const USERNAME = 'A4';
  const FULLNAME = 'A5';
  const USERROLL = 'A6';
}

/**
 * This controller ensures requested actions have security clearance before
 * invoking.
 *
 * @package mindplex-commons-web
 * @author Abel Perez 
 */
abstract class SecureController extends GenericController
{
  /**
   * Applies security checks to action request before routing.
   */
  public function route() {
    $action = $this->validateAction();

    /**
     * By pass security checks if requested action is public.
     */
    if (in_array($action, $this->getPublicActions())) {
      parent::fastRoute($action);
    }

    /**
     * if requester is not logged in deny access and render login page.
     */
    if (! $this->safe()) {
      $this->deny();	
    }

    // access allowed.
    parent::fastRoute($action);
  }
	
  /**
   * default public actions.
   *
   * @return array  
   */
  public function getPublicActions() {
    return array('index', 'login', 'logout', 'heartbeat');		
  }	
	
  /**
   * Authenticate login based on the specified username and password.
   */
  protected function login() {
    Session::start();
    header('Cache-control: private');

    $username = $this->get('username', '');
    $password = $this->get('password', '');
    $site = __SITE__;

    if ($username === '' || $password === '') {
      return $this->denyAccess('Username or Password cannot be empty.');
    }
    $userAccount = $this->getUserAccount($username, $site);
    if (empty($userAccount)) {
      return $this->denyAccess('Invalid Username');
    }
    if ($userAccount->password != $password) {
      return $this->denyAccess('Invalid Password');
    }

    Session::put(Auth::ACCESS, TRUE);
    Session::put(Auth::USERID, $userAccount->id);
    Session::put(Auth::USERNAME, $userAccount->username);
    Session::put(Auth::FULLNAME, $userAccount->fullname);
    Session::put(Auth::USERROLL, $userAccount->role);
    $this->home();	
  }
	
  /**
   * logout and destroy the current session.
   */
  protected function logout() {
    if (isset($_COOKIE[Session::getName()])) {
      setcookie(Session::getName(), '', time() - 42000, '/');
    }
    Session::destroy();
    $this->viewLogin();		
  }

  /**
   * Denies access based on the specified reason.
   */	
  protected function denyAccess($reason) {
    Session::put(Auth::ACCESS, FALSE);
    Session::put(Auth::USERNAME, NULL);
    $this->badLogin($reason);
  }
		
  /**
   * Checks in the session if the current request has been granted access.
   *
   * @return boolean   
   */
  public function safe() {
    return Session::get(Auth::ACCESS);
  }
	
  /**
   * Redirects to default login page.
   */
  public function deny($target = __DEFAULT_LOGIN_PAGE__) {
    $this->redirect($target);
  }
		
  /**
   * Redirects to default home page.
   */
  protected function home() {
    $this->redirect($this->getHome());
  }

  /**
   * Redirects to default Login page.
   */
  protected function viewLogin($target = __DEFAULT_LOGIN_PAGE__) {
    $this->redirect($target);
  }
	
  /**
   * Renders default Login page with error message.
   *
   * Note: this method does not redirect to the specified view, it simply renders 
   * the view file.
   */
  protected function badLogin($message, $target = 'login.php') {
    if ($message == null || $message == '') {
      $message = 'Invalid login attempt.';
    }
    require($target);
    exit();
  }

  /**
   * Get's the user account based on the specified username and site.
   *
   * @param $username the username to lookup
   * @param $site the site the given user belongs too
   *
   * @return UserAccount
   */
  protected function getUserAccount($username, $site) {
    $dao = new UserAccount();
    return $dao->findByAttributeNames(array(
      'Username' => $username, 'Site' => $site), true);	
    }			
}

?>