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
 * BaseGateway encapsulates common functionality required by all gateways.
 *
 * @todo consider encapsulating METHOD.
 *
 * @package mindplex-commons-gateway 
 * @author Abel Perez
 */
abstract class BaseGateway
{
    /**
     * Credentials required for this gateway.
     */
    private $credentials = '';

    /**
     * Get's the query string for this gateway.
     *
     * TODO: consider using http_build_query
     */
    abstract public function getQueryString($model);

    /**
     * Get's the endpoint for this endpoint.
     */	
    abstract public function getEndpoint();

    /**
     * Get's the partner name for this gateway.
     */	
    abstract public function getPartnerName();

    /**
     * Post's the specified model to this gateway's endpoint.
     */	
    abstract public function post($model, $debug = false);

    /**
     * Assigns this gateway's response to a GatewayResponse instance.
     */	
    abstract public function assignResponse($response);

    /**
     * Checks if this gateway is active.
     */
    public function isActive() {
        return true;
    }

    /**
     * Get's this gateway's request payload.
     */
    public function getPayload() {
        return '';
    }

    /**
     * Set's this gateway's credentials.
     */
    public function setCredentials($username, $password) {
        $this->credentials = sprintf("%s:%s", $username, $password);
    }

    /**
     * Get's this gateway's credentials.
     */
    public function getCredentials() {
        return $this->credentials;
    }

    /**
     * Tracks the disposition of each gateway request.
     */
    public function track($response) {
    }	

    /**
     * Invoke's HTTP request based on the specified HTTP method, request
     * payload and with the required authentication.
     */
    public function call($method, $payload, $secure) {
        $curl = curl_init();
        $endpoint = $this->getEndpoint();

		// HTTP GET
        if ($method == 'GET') {
            $endpoint .= '?' . $payload;
        }
		
        curl_setopt($curl, CURLOPT_URL, $endpoint);

        // HTTP AUTH
        if ($secure) {
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, $this->getCredentials());
        }

        // HTTP POST
        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        }

        // HTTP PUT
        if ($method == 'PUT') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: '.strlen($this->$payload)));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        }

        // HTTP DEL
        if ($method == 'DEL') {
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, Array('Content-Type:application/atom+xml'));
            curl_setopt($curl, CURLOPT_FAILONERROR, TRUE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        // RAW JSON
        if ($method == 'JSON') {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json',
                    'Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Expect:'));

        $data = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $response = new GatewayResponse();
        $response->setEndpoint($endpoint);
        $response->setHttpMethod($method);
        $response->setHttpStatus($status);
        $response->setRequest($payload);
        $response->setRequest($data);
        $response->setPartnerName($this->getPartnerName());
        $response->setCredentials($this->getCredentials());

        $this->track($response);
        return $response;
    }

    /**
     * Constructs a key value pair representation of the specified model.
     *
     * Note: callback function should conform to some notion of a 
     * key/value pair.
     */	
    public function keyvalue($model, $callback) {

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
