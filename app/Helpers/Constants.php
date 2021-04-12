<?php

namespace App\Helpers;

class Constants
{
    const TRUE = 1;
    const FALSE = 0;

    //response code (More at: vendor\symfony\http-foundation\Response.php)
    const STATUS_CODE_SUCCESS = 200;
    const STATUS_CODE_BAD_REQUEST = 400;
    const STATUS_CODE_UNAUTHORIZED = 401;
    const STATUS_CODE_NOT_FOUND = 404;
    const STATUS_CODE_ERROR = 500;

    //custom error status
    const TOKEN_INVALID = 'token_invalid';
    const TOKEN_EXPIRED = 'token_expired';
    const TOKEN_BLACKLISTED = 'token_blacklisted';
    const TOKEN_NOT_FOUND = 'token_not_found';
}
