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
 * GatewayResponse contains response data from a Gateway invocation.
 *
 * @package mindplex-commons-gateway
 * @author Abel Perez
 */
class GatewayResponse 
{
    private $partnerName;
    private $endpoint;
    private $status;
    private $message;
    private $request;
    private $response;
    private $data;
    private $httpStatus;
    private $httpMethod;	
	
    /**
     * Constructs this gateway response with the specified status.
     */	
    public function GatewayResponse($status = 'none') {
        $this->status = $status;
    }

    public function getPartnerName() {
        return $this->partnerName;
    } 

    public function getEndpoint() {
        return $this->endpoint;
    }

    public function getStatus() {
        return $this->status;
    } 
	
    public function getMessage() {
        return $this->message;
    } 	

    public function getRequest() {
        return $this->request;
    } 	

    public function getResponse() {
        return $this->response;
    } 	

    public function setPartnerName($partnerName) {
        $this->partnerName = $partnerName;
    } 

    public function setEndpoint($endpoint) {
        $this->endpoint = $endpoint;
    }

    public function setStatus($status) {
        $this->status = $status;
    } 

    public function setMessage($message) {
        $this->message = $message;
    } 	

    public function setRequest($request) {
        $this->request = $request;
    } 	

    public function setResponse($response) {
        $this->response = $response;
    }	

    public function setData($data) {
        $this->data = $data;
    }		

    public function setHttpStatus($httpStatus) {
        $this->httpStatus = $httpStatus;
    }

    public function setHttpMethod($httpMethod) {
        $this->httpMethod = $httpMethod;
    }	

    public function getData() {
        return $this->data;
    }		

    public function getHttpStatus() {
        return $this->httpStatus;
    }		
}

?>
