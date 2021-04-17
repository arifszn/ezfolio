const CoreConstants = {
    TRUE: 1,
    FALSE: 0,

    //error status
    STATUS_CODE_SUCCESS: 200,
    STATUS_CODE_BAD_REQUEST: 400,
    STATUS_CODE_UNAUTHORIZED: 401,
    STATUS_CODE_NOT_FOUND: 404,
    STATUS_CODE_ERROR: 500,

    //custom error status
    TOKEN_INVALID: 'token_invalid',
    TOKEN_EXPIRED: 'token_expired',
    TOKEN_BLACKLISTED: 'token_blacklisted',
    TOKEN_NOT_FOUND: 'token_not_found',

    settings: {
        SITE_NAME: 1,
        ACCENT_COLOR: 2,
        SHORT_MENU: 3,
        LOGO: 4,
        FAVICON: 5,
        COVER: 6,
        MENU_LAYOUT: 7,
        MENU_COLOR: 8,
        NAV_COLOR: 9,
    },

    portfolioConfig: {
        TEMPLATE: 1,
        ACCENT_COLOR: 2,
        GOOGLE_ANALYTICS_ID: 3,
        MAINTENANCE_MODE: 4,
        VISIBILITY_ABOUT: 5,
        VISIBILITY_SKILL: 6,
        VISIBILITY_EDUCATION: 7,
        VISIBILITY_EXPERIENCE: 8,
        VISIBILITY_PROJECT: 9,
        VISIBILITY_SERVICE: 10,
        VISIBILITY_CONTACT: 11,
        VISIBILITY_FOOTER: 12,
        SCRIPT_HEADER: 13,
        SCRIPT_FOOTER: 14,
        META_TITLE: 15,
        META_AUTHOR: 16,
        META_DESCRIPTION: 17,
        META_IMAGE: 18,
        VISIBILITY_CV: 19,
        VISIBILITY_SKILL_PROFICIENCY: 20,
    }
}

export default CoreConstants;