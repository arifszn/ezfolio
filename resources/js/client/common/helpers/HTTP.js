import axios from "axios";
import { removeApiToken, saveApiToken } from "../../admin/redux/ActionCreators";
import Utils from "../helpers/Utils";
import Constants from "./Constants";
import Routes from "./Routes";

/**
 * Custom axios instance with interceptor
 */
const HTTP = axios.create();

const refreshToken = async (store) => {
    const apiToken = (localStorage.getItem("apiToken") !== 'undefined' && localStorage.getItem("apiToken") !== null) ?
                        localStorage.getItem("apiToken") : null;
    
    if (apiToken) {
        return await HTTP.post(Routes.api.admin.refreshToken, {
            headers: {
                Authorization: `Bearer ${apiToken}`
            }
        })
        .then((response) => {
            Utils.handleSuccessResponse(response, () => {
                store.dispatch(saveApiToken({
                    apiToken: response.data.payload.access_token,
                }));
            })
        })
        .catch(error => {
            Utils.handleException(error, () => {
                throw error;
            });
        });
    }
}

export const setupInterceptors = (store) => {
    const isPrivate = (config={}) => {
        return (Object.prototype.hasOwnProperty.call(config, "isPrivate") && !config.isPrivate) ? false : true
    }

    // Add interceptors
    HTTP.interceptors.request.use(
        request => requestHandler(request)
    )

    HTTP.interceptors.response.use(
        response => successHandler(response),
        error => errorHandler(error)
    )

    //Sending request
    const requestHandler = (request) => {
        if (isPrivate(request)) {
            const apiToken = (localStorage.getItem("apiToken") !== 'undefined' && localStorage.getItem("apiToken") !== null) ?
                        localStorage.getItem("apiToken") : null;
            
            if (apiToken) {
                request.headers['Authorization'] = `Bearer ${apiToken}`;
            }
        }
        return request
    }

    //Request failed
    const errorHandler = async (error) => {
        if (isPrivate(error.config)) {
            let responseStatus = (typeof error.response !== 'undefined' && typeof error.response.data !== 'undefined' && typeof error.response.data.status !== 'undefined') ? error.response.data.status : (typeof error.response !== 'undefined' && typeof error.response.status !== 'undefined' ? error.response.status : Constants.STATUS_CODE_SUCCESS);
            
            if (responseStatus === Constants.STATUS_CODE_ERROR) {
                if (typeof error.response.data.payload !== 'undefined' && (error.response.data.payload === Constants.TOKEN_INVALID || error.response.data.payload === Constants.TOKEN_BLACKLISTED)) {
                    store.dispatch(removeApiToken());
                }
            } else if (responseStatus === Constants.STATUS_CODE_UNAUTHORIZED) {
                if (typeof error.response.data.payload !== 'undefined' && error.response.data.payload === Constants.TOKEN_EXPIRED) {
                    //refresh apiToken
                    const originalRequest = error.config;
                    try {
                        await refreshToken(store);
                        return HTTP(originalRequest);

                    } catch (error) {
                        Utils.handleException(error);
                    }
                }
            }
        }
        return Promise.reject({ ...error })
    }

    //Request succeeded
    const successHandler = (response) => {
        if (isPrivate(response.config)) {
            //
        }
        return response
    }
}

export default HTTP;