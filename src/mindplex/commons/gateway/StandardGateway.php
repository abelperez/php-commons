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
 *
 *
 * @package mindplex-commons-gateway
 * @author Abel Perez
 */
class StandardGateway extends GenericGateway
{
    /**
     * Tracks the disposition of each gateway request to the tracking 
     * data store.
     */
    public function track($response) {
        try {
            $event = new GatewayTracker();
            $event->partner = $response->getPartnerName();
            $event->endpoint = $response->getEndpoint();
            $event->method = $response->getHttpMethod();
            $event->payload = $response->getPayload();
            $event->response = htmlspecialchars($response->getData(), ENT_COMPAT);
            $event->status = $response->getStatus();
            $event->credentials = $response->getCredentials();
            $event->saveOrUpdate();
        } catch (Exception $exception) {
            // TODO: log and possibly fire email notification.
        }		
    }
}

?>