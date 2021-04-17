<?php

namespace App\Helpers;

class CoreConstants
{
    const TRUE = 1;
    const FALSE = 0;

    // response code (More at: vendor\symfony\http-foundation\Response.php)
    const STATUS_CODE_SUCCESS = 200;
    const STATUS_CODE_BAD_REQUEST = 400;
    const STATUS_CODE_UNAUTHORIZED = 401;
    const STATUS_CODE_NOT_FOUND = 404;
    const STATUS_CODE_ERROR = 500;

    // custom error status
    const TOKEN_INVALID = 'token_invalid';
    const TOKEN_EXPIRED = 'token_expired';
    const TOKEN_BLACKLISTED = 'token_blacklisted';
    const TOKEN_NOT_FOUND = 'token_not_found';


    #region [PortfolioConfig table constants]

    const PORTFOLIO_CONFIG__TEMPLATE = 1;
    const PORTFOLIO_CONFIG__ACCENT_COLOR = 2;
    const PORTFOLIO_CONFIG__GOOGLE_ANALYTICS_ID = 3;
    const PORTFOLIO_CONFIG__MAINTENANCE_MODE = 4;

    // visibility
    const PORTFOLIO_CONFIG__VISIBILITY_ABOUT = 5;
    const PORTFOLIO_CONFIG__VISIBILITY_SKILL = 6;
    const PORTFOLIO_CONFIG__VISIBILITY_EDUCATION = 7;
    const PORTFOLIO_CONFIG__VISIBILITY_EXPERIENCE = 8;
    const PORTFOLIO_CONFIG__VISIBILITY_PROJECT = 9;
    const PORTFOLIO_CONFIG__VISIBILITY_SERVICE = 10;
    const PORTFOLIO_CONFIG__VISIBILITY_CONTACT = 11;
    const PORTFOLIO_CONFIG__VISIBILITY_FOOTER = 12;

    const PORTFOLIO_CONFIG__SCRIPT_HEADER = 13;
    const PORTFOLIO_CONFIG__SCRIPT_FOOTER = 14;

    const PORTFOLIO_CONFIG__META_TITLE = 15;
    const PORTFOLIO_CONFIG__META_AUTHOR = 16;
    const PORTFOLIO_CONFIG__META_DESCRIPTION = 17;
    const PORTFOLIO_CONFIG__META_IMAGE = 18;

    const PORTFOLIO_CONFIG__VISIBILITY_CV = 19;
    const PORTFOLIO_CONFIG__VISIBILITY_SKILL_PROFICIENCY = 20;

    #endregion


    #region [Setting table constants]

    const SETTING__SITE_NAME = 1;
    const SETTING__ACCENT_COLOR = 2;
    const SETTING__SHORT_MENU = 3;
    const SETTING__LOGO = 4;
    const SETTING__FAVICON = 5;
    const SETTING__COVER = 6;
    const SETTING__MENU_LAYOUT = 7;
    const SETTING__MENU_COLOR = 8;
    const SETTING__NAV_COLOR = 9;

    #endregion
}
