import { notification, message as tinyMessage } from 'antd';
import CoreConstants from './CoreConstants';
import { changeAntdTheme } from 'mini-dynamic-antd-theme';

/**
 * Display notification
 * 
 * @param string message 
 * @param string type 
 * @param string/null title 
 * @param boolean sticky 
 */
const showNotification = (message = 'Something went wrong', type = 'error', title = null, sticky = false) => {
    notification[type]({
        message: title ? title : type[0].toUpperCase() + type.slice(1),
        description: message,
        placement: 'bottomRight',
        duration: sticky ? 0 : 4.5
    });
};

/**
 * Display tiny notification
 * 
 * @param string message 
 * @param string type 
 */
const showTinyNotification = (message = 'Something went wrong', type = 'error') => {
    tinyMessage[type](message);
};

/**
 * Ellipse long text
 * 
 * @param string str 
 * @param number length 
 * @param string ending 
 */
const textEllipsis = (str, length, ending = '...') => {
    if (length == null) {
      length = 100;
    }
    if (str.length > length) {
      return str.substring(0, length - ending.length) + ending;
    } else {
      return str;
    }
};

/**
 * Return a random color code in hex format
 */
const randomHexColor = () => {
    return `#${Math.floor(Math.random()*16777215).toString(16)}`;
}

/**
 * Handle error exception
 * 
 * @param Error error 
 * @param Function/null callback 
 */
const handleException = (error, callback = null) => {
    console.error(error);

    let errorMessage = 'Something went wrong';

    if (typeof error === 'string') {
        try {
            error = JSON.parse(error);
        } catch (error) {
            // do nothing
        }
    }

    if (typeof error.message !== 'undefined') {
        errorMessage = error.message;
    }

    if (typeof error.response !== 'undefined' && typeof error.response.data !== 'undefined') {
        if (typeof error.response.data.message !== 'undefined') {
            if ((typeof error.response.data.status !== 'undefined') && (error.response.data.status === CoreConstants.STATUS_CODE_BAD_REQUEST)) {
                if (error.response.data.payload && typeof error.response.data.payload ==='object') {
                    handleBadRequest(error.response.data.payload);
                } else {
                    showNotification(error.response.data.message, 'error');
                }
            } else if ((typeof error.response.data.status !== 'undefined') && (error.response.data.status !== CoreConstants.STATUS_CODE_SUCCESS)) {
                showNotification(error.response.data.message, 'error');
            } else {
                showNotification(error.response.data.message, 'error');
            }
        }
    } else {
         showNotification(errorMessage, 'error');
    }

    if (callback) {
        return callback();
    }
}

/**
 * Handle success Response
 * 
 * @param Response response 
 * @param Function successCallback
 * @param Function/null errorCallback 
 */
const handleSuccessResponse = (response, successCallback, errorCallback = null) => {
    if (typeof response.data !== 'undefined') {
        if ((typeof response.data.status !== 'undefined') && (response.data.status === CoreConstants.STATUS_CODE_BAD_REQUEST)) {
            if (response.data.payload && typeof response.data.payload ==='object') {
                handleBadRequest(response.data.payload);
            } else {
                showNotification(response.data.message, 'error');
            }
        } else if ((typeof response.data.status !== 'undefined') && (response.data.status !== CoreConstants.STATUS_CODE_SUCCESS)) {
            showNotification(response.data.message, 'error');
            
            if (errorCallback) {
                return errorCallback();
            }
        } else if ((typeof response.data.status !== 'undefined') && (response.data.status === CoreConstants.STATUS_CODE_SUCCESS)) {
            return successCallback();
        }
    }
}

/**
 * Handle bad request
 * 
 * @param errorObject 
 */
const handleBadRequest = (errorObject) => {
    for (var key of Object.keys(errorObject)) {
        let errors = errorObject[key];
        errors.forEach(error => {
            showNotification(error, 'error', false);
        });
    }
}

/**
 * Change accent color of the system
 * 
 *  * @param color 
 */
const changeAccentColor = (color) => {
    changeAntdTheme(color);
}

/**
 * Portfolio templates
 */
const templates = [
    {
        id: 'procyon',
        title: 'Procyon',
        image: window.location.origin + '/' +'assets/common/img/templates/procyon.png',
    },
    {
        id: 'rigel',
        title: 'Rigel',
        image: window.location.origin + '/' +'assets/common/img/templates/rigel.png',
    },
    {
        id: 'vega',
        title: 'Vega',
        image: window.location.origin + '/' +'assets/common/img/templates/vega.png',
    },
];

/**
 * Utility helper
 */
const Utils = {
    client: window.location.origin,
    backend: window.location.origin,
    apiVersion: 'v1',
    showNotification,
    showTinyNotification,
    textEllipsis,
    randomHexColor,
    handleException,
    handleBadRequest,
    handleSuccessResponse,
    changeAccentColor,
    templates,
}

export default Utils;