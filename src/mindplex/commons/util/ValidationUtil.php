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
 * ValidationUtil contains operations for validating different types of data formats
 * and constraints e.g., email format, phone numbers, numeric fields etc.
 *
 * @package mindplex-commons-util
 * @author Abel Perez
 */
class ValidationUtil
{
    /**
     * Checks if the specified string item is null or empty.
     *
     * @param string $item the string to check for null or empty.
     *
     * @returns true if the specified string item is not null or empty.
     */
    public static function isNullOrEmpty($item) {
        return ($item == null || $item == '');
    }

    /**
     * Validates the specified phone number by checking the format of the phone number.  
     * The expected valid format is a 10 digit string.
     *
     * @param string $phone the phone number to validate.
     *
     * @returns true if the specified phone number is valid.
     */
    public static function isValidPhone($phone) {

        if (ValidationUtil::isNullOrEmpty($phone)) return false;

        $target = str_replace(array('-', '(', ')'), '', $phone);
        if (! is_numeric($target)) return;

        // verify is 10 digits
        if (strlen($phone) != 10) {
            return false;
        }
        return true;
    }

    /**
     * Validates the specified phone number by checking the format of the phone number.  
     * The expected format is the following: 0-000-000-0000 or 000-000-0000.
     *
     * @param string $phone the phone number to validate.
     *
     * @returns true if the specified phone number is valid.
     */
    public static function isValidFormattedPhone($phone) {
        if (ValidationUtil::isNullOrEmpty($phone)) return false;
        return ereg('^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$', $phone);
    }

    /**
     * Validates the specified email address contains the correct email address format 
     * and checks that the domain exists.
     *
     * This method has been borrowed from Douglas Lovell more info on how this method 
     * was concluded can be found here:
     *
     * http://www.linuxjournal.com/article/9585
     *
     * @param string $email the email to validate.
     * @param boolean $dnsCheck true if domain part of specified email should be checked 
     * against DNS records.
     *
     * @returns true if the specified email is valid.
     */
    public static function isValidEmail($email, $dnsCheck = true) {
        $isValid = true;
        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex) {
            $isValid = false;

        } else {
            $domain = substr($email, $atIndex+1);
            $local = substr($email, 0, $atIndex);
            $localLen = strlen($local);
            $domainLen = strlen($domain);

            if ($localLen < 1 || $localLen > 64) {
                // local part length exceeded
                $isValid = false;

            } else if ($domainLen < 1 || $domainLen > 255) {
                // domain part length exceeded
                $isValid = false;

            } else if ($local[0] == '.' || $local[$localLen-1] == '.') {
                // local part starts or ends with '.'
                $isValid = false;

            } else if (preg_match('/\\.\\./', $local)) {
                // local part has two consecutive dots
                $isValid = false;

            } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
                // character not valid in domain part
                $isValid = false;

            } else if (preg_match('/\\.\\./', $domain)) {
                // domain part has two consecutive dots
                $isValid = false;

            } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                    str_replace("\\\\","",$local))) {
                // character not valid in local part unless 
                // local part is quoted
                if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
                    $isValid = false;
                }
            }

            if ($dnsCheck) {
                if ($isValid && !(checkdnsrr($domain, "MX") ||
                        checkdnsrr($domain, "A"))) {
                    // domain not found in DNS
                    $isValid = false;
                }
            }
        }
        return $isValid;
    }
}

?>