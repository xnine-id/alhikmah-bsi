<?php

namespace App\Constants;

class BsiResponseCode
{
    // Auth Response Codes
    public const AUTH_SUCCESS = 2000000;
    public const AUTH_ERROR = 4030000;
    public const AUTH_DB_ERROR = 5000099;

    public const MSG_AUTH_SUCCESS = 'Auth Success';
    public const MSG_AUTH_ERROR = 'Auth Error';
    public const MSG_AUTH_DB_ERROR = 'Internal Server Error';

    // Inquiry Response Codes
    public const INQUIRY_SUCCESS = 2002400;
    public const INQUIRY_INVALID_FIELD_FORMAT = 4002401;
    public const INQUIRY_INVALID_MANDATORY_FIELD = 4002402;
    public const INQUIRY_UNAUTHORIZED_ACCESS = 4012400;
    public const INQUIRY_UNAUTHORIZED_TOKEN = 4012401;
    public const INQUIRY_INVALID_DATA = 4042411; // VA available but not ready to be paid
    public const INQUIRY_BILL_NOT_FOUND = 4042412;
    public const INQUIRY_BILL_ALREADY_PAID = 4042414;
    public const INQUIRY_INVALID_BILL_NUMBER_FORMAT = 4042419;
    public const INQUIRY_BILL_EXPIRED = 4042420;
    public const INQUIRY_GENERAL_ERROR = 5002400;
    public const INQUIRY_DB_ERROR = 5002499;
    public const INQUIRY_TIMEOUT = 5042400;

    public const MSG_INQUIRY_SUCCESS = 'Success';
    public const MSG_INQUIRY_INVALID_FIELD_FORMAT = 'Invalid Field Format';
    public const MSG_INQUIRY_INVALID_MANDATORY_FIELD = 'Field {xyz} is not exists';
    public const MSG_INQUIRY_UNAUTHORIZED_ACCESS = 'Unauthorized Access';
    public const MSG_INQUIRY_UNAUTHORIZED_TOKEN = 'Invalid Token {accessToken}';
    public const MSG_INQUIRY_INVALID_DATA = 'Invalid data';
    public const MSG_INQUIRY_BILL_NOT_FOUND = 'Bill not found';
    public const MSG_INQUIRY_BILL_ALREADY_PAID = 'Bill already paid';
    public const MSG_INQUIRY_INVALID_BILL_NUMBER_FORMAT = 'Invalid Bill number format';
    public const MSG_INQUIRY_BILL_EXPIRED = 'Bill Expired';
    public const MSG_INQUIRY_GENERAL_ERROR = 'General Error';
    public const MSG_INQUIRY_DB_ERROR = 'DB Error';
    public const MSG_INQUIRY_TIMEOUT = 'Timeout';

    // Payment Response Codes
    public const PAYMENT_SUCCESS = 2002500;
    public const PAYMENT_INVALID_FIELD_FORMAT = 4002501;
    public const PAYMENT_INVALID_MANDATORY_FIELD = 4002502;
    public const PAYMENT_UNAUTHORIZED_ACCESS = 4012500;
    public const PAYMENT_UNAUTHORIZED_TOKEN = 4012501;
    public const PAYMENT_INVALID_DATA = 4042511; // VA available but not ready to be paid
    public const PAYMENT_BILL_NOT_FOUND = 4042512;
    public const PAYMENT_AMOUNT_NOT_VALID = 4042513; // Payment amount not valid (e.g., less than regulation)
    public const PAYMENT_BILL_ALREADY_PAID = 4042514;
    public const PAYMENT_INVALID_BILL_NUMBER_FORMAT = 4042519;
    public const PAYMENT_GENERAL_ERROR = 5002500;
    public const PAYMENT_DB_ERROR = 5002599;
    public const PAYMENT_TIMEOUT = 5042500;

    public const MSG_PAYMENT_SUCCESS = 'Success';
    public const MSG_PAYMENT_INVALID_FIELD_FORMAT = 'Invalid Field Format';
    public const MSG_PAYMENT_INVALID_MANDATORY_FIELD = 'Field {xyz} is not exists';
    public const MSG_PAYMENT_UNAUTHORIZED_ACCESS = 'Unauthorized Access';
    public const MSG_PAYMENT_UNAUTHORIZED_TOKEN = 'Invalid Token {accessToken}';
    public const MSG_PAYMENT_INVALID_DATA = 'Invalid data';
    public const MSG_PAYMENT_BILL_NOT_FOUND = 'Bill not found';
    public const MSG_PAYMENT_AMOUNT_NOT_VALID = 'Payment Amount not valid';
    public const MSG_PAYMENT_BILL_ALREADY_PAID = 'Bill already paid';
    public const MSG_PAYMENT_INVALID_BILL_NUMBER_FORMAT = 'Invalid Bill number format';
    public const MSG_PAYMENT_GENERAL_ERROR = 'General Error';
    public const MSG_PAYMENT_DB_ERROR = 'DB Error';
    public const MSG_PAYMENT_TIMEOUT = 'Timeout';

    /**
     * Get message by response code
     */
    public static function getMessage($code, $replace = [])
    {
        $messages = [
            self::AUTH_SUCCESS => self::MSG_AUTH_SUCCESS,
            self::AUTH_ERROR => self::MSG_AUTH_ERROR,
            self::AUTH_DB_ERROR => self::MSG_AUTH_DB_ERROR,
            
            self::INQUIRY_SUCCESS => self::MSG_INQUIRY_SUCCESS,
            self::INQUIRY_INVALID_FIELD_FORMAT => self::MSG_INQUIRY_INVALID_FIELD_FORMAT,
            self::INQUIRY_INVALID_MANDATORY_FIELD => self::MSG_INQUIRY_INVALID_MANDATORY_FIELD,
            self::INQUIRY_UNAUTHORIZED_ACCESS => self::MSG_INQUIRY_UNAUTHORIZED_ACCESS,
            self::INQUIRY_UNAUTHORIZED_TOKEN => self::MSG_INQUIRY_UNAUTHORIZED_TOKEN,
            self::INQUIRY_INVALID_DATA => self::MSG_INQUIRY_INVALID_DATA,
            self::INQUIRY_BILL_NOT_FOUND => self::MSG_INQUIRY_BILL_NOT_FOUND,
            self::INQUIRY_BILL_ALREADY_PAID => self::MSG_INQUIRY_BILL_ALREADY_PAID,
            self::INQUIRY_INVALID_BILL_NUMBER_FORMAT => self::MSG_INQUIRY_INVALID_BILL_NUMBER_FORMAT,
            self::INQUIRY_BILL_EXPIRED => self::MSG_INQUIRY_BILL_EXPIRED,
            self::INQUIRY_GENERAL_ERROR => self::MSG_INQUIRY_GENERAL_ERROR,
            self::INQUIRY_DB_ERROR => self::MSG_INQUIRY_DB_ERROR,
            self::INQUIRY_TIMEOUT => self::MSG_INQUIRY_TIMEOUT,

            self::PAYMENT_SUCCESS => self::MSG_PAYMENT_SUCCESS,
            self::PAYMENT_INVALID_FIELD_FORMAT => self::MSG_PAYMENT_INVALID_FIELD_FORMAT,
            self::PAYMENT_INVALID_MANDATORY_FIELD => self::MSG_PAYMENT_INVALID_MANDATORY_FIELD,
            self::PAYMENT_UNAUTHORIZED_ACCESS => self::MSG_PAYMENT_UNAUTHORIZED_ACCESS,
            self::PAYMENT_UNAUTHORIZED_TOKEN => self::MSG_PAYMENT_UNAUTHORIZED_TOKEN,
            self::PAYMENT_INVALID_DATA => self::MSG_PAYMENT_INVALID_DATA,
            self::PAYMENT_BILL_NOT_FOUND => self::MSG_PAYMENT_BILL_NOT_FOUND,
            self::PAYMENT_AMOUNT_NOT_VALID => self::MSG_PAYMENT_AMOUNT_NOT_VALID,
            self::PAYMENT_BILL_ALREADY_PAID => self::MSG_PAYMENT_BILL_ALREADY_PAID,
            self::PAYMENT_INVALID_BILL_NUMBER_FORMAT => self::MSG_PAYMENT_INVALID_BILL_NUMBER_FORMAT,
            self::PAYMENT_GENERAL_ERROR => self::MSG_PAYMENT_GENERAL_ERROR,
            self::PAYMENT_DB_ERROR => self::MSG_PAYMENT_DB_ERROR,
            self::PAYMENT_TIMEOUT => self::MSG_PAYMENT_TIMEOUT,
        ];

        $message = $messages[$code] ?? 'Unknown Error';
        
        foreach ($replace as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }
        
        return $message;
    }
}
