import Types from "./Types";
import Axios from "axios";

export const initializeGlobalState = (state) => ({
    type: Types.INITIALIZE_GLOBAL_STATE,
    payload: state
});

export const setGlobalState = (states) => ({
    type: Types.SET_GLOBAL_STATE,
    payload: states
});

export const fetchUser = (apiToken) => {
    
};

export const fetchAccentColor = (apiToken) => {
    
};