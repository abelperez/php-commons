<?php

define('GATEWAY_GET', 'GET');
define('GATEWAY_POST', 'POST');

/**
 * GenericGateway encapsulates generic functionality 
 * required by all HTTP gateways.
 *
 * @author Mindplex Media (support@mindplexmedia.com)
 */
abstract class GenericGateway
{
	/**
	 * Authentication credentials for HTTP Basic 
	 * Authentication.
	 */
	private $credentials = '';

	/**
	 * Get's the base endpoint for this gateway.
	 *
	 * @return the base endpoint for this gateway.
	 */	
	abstract public function getEndpoint();
	
	/**
	 * Assigns the specfied response to a custom
	 * response type.
	 *
	 * @param $response raw HTTP response.
	 *
	 * @return the specfied response as a custom
	 * response type.
	 */	
	abstract public function assignResponse($response);

	/**
	 * TRUE if this gateway is active otherwise FALSE.
	 *
	 * @return TRUE if this gateway is active otherwise 
	 * FALSE.
	 */
	public function isActive() {
		return TRUE;
	}
	
	/**
	 * Set's HTTP Basic Authentication credentials 
	 * in HTTP header format "username:password".
	 *
	 * @param $username username to authenticate.
	 * @param $password password to authenticate.
	 */
	public function setCredentials($username, $password) {
		$this->credentials = sprintf("%s:%s", $username, $password);
	}
	
	/**
	 * Get's HTTP Basic Authentication credentials.
	 *
	 * @return HTTP Basic Authentication credentials.
	 */
	public function getCredentials() {
		return $this->credentials;
	}
		
	/**
	 * Low level HTTP request.
	 *
	 * @param $method the HTTP method type i.e., GET, 
	 * POST, PUT, and DELETE.
	 * @param $endpoint the HTTP endpoint url to invoke.
	 * @param $params the HTTP request parameters.
	 * @param $secure TRUE if HTTP Basic Authentication
	 * is required.
	 *
	 * @return GatewayResponse with the disposition of 
	 * invoking the specified endpoint.  
	 */
	public function call($method, $endpoint, $params, $secure) {
		$curl = curl_init();
		
		// prepare HTTP GET
		if ($method == 'GET') {
			if (count($params) != 0) {
				$endpoint .= '?' . $params;
			}
		}
		
		curl_setopt($curl, CURLOPT_URL, $endpoint);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		
		// prepare credentials (HTTP format)
		if ($secure) {
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($curl, CURLOPT_USERPWD, $this->getCredentials());
		}
		
		// prepare HTTP POST
		if ($method == 'POST') {
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		}
		
		// prepare HTTP PUT (not supported)
		if ($method == 'PUT') {
			/*
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: '.strlen($this->getPayload())));
			curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
			*/
		}
		
		// prepare HTTP DEL (needs to be tested)
		if ($method == 'DEL') {
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:application/atom+xml"));
			curl_setopt($curl, CURLOPT_FAILONERROR, TRUE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
		}
		
		if ($method == 'JSON') {
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				'Accept: application/json', 
				'Content-Type: application/json'));
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		}
		
		// prepare default headers
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		//curl_setopt($curl, CURLOPT_HTTPHEADER, array('Expect:'));
		
		// invoke HTTP request
		$data = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		
		$response = new GatewayResponse();
		$response->setData($data);
		$response->setHttpStatus($status);
		$response->setRequest($params);
        $response->setEndpoint($endpoint);
		
		try {
			// track gateway operation
			/*
			$event = new GatewayTracker();
			$event->partner = $this->getPartnerName();
            $event->endpoint = $this->getEndpoint();
			$event->method = $method;
			$event->payload = $params;
		    $event->response = htmlspecialchars($data, ENT_COMPAT);
			$event->status = $status;
			$event->credentials = $this->getCredentials();
            $event->saveOrUpdate();
			*/
		} catch (Exception $exception) {
			// TODO: fire email notification.
            echo "Failed to track: ".$exception->getMessage();
		}
		return $response;
	}
		
	/**
	 * Constructs a key value pair representation
	 * of the specified model.
	 *
	 * Note: callback function should conform
	 * to sosme notion of a key/value pair.
	 */	
	public function assignRequest($model, $callback) {
		
		// if not a valid active record bail.
		if (! method_exists($model, 'getAttributeNames')) {
			return '';
		}
		
		$result = '';
		foreach ($model->getAttributeNames() as $attribute) {
			
			// invoke specified callback that generates key/value pair.
			if (is_callable($callback)) { 
				$pair = call_user_func($callback, $attribute, $model->$attribute);
				
			} else {
				// manually construct key/value pair
				$pair = $attribute.'='.$model->$attribute.'&';
				
			}
			$result .= $pair;
		}
		return $result;
	}
}

?>
